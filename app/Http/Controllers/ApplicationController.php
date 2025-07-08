<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Enums\ApprovalStatus;
use App\Enums\ProductType;
use App\Models\ApprovalHistory;
use Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Auth;
use App\Models\Loan;
use App\Models\LoanSchedule;

class ApplicationController extends Controller
{
    /*============================================================
     * Display a listing of the applications.
     */
    public function index(Request $request)
    {
        $query = Application::query();

        // Filter by search (application number)
        if ($request->filled('search')) {
            $query->where('application_number', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by product type (via polymorphic relation)
        if ($request->filled('product_type')) {
            if ($request->product_type === 'loan') {
                $query->where('model_type', \App\Models\Loan::class);
            } else {
                $query->whereHasMorph(
                    'model',
                    [\App\Models\Loan::class, \App\Models\Product::class],
                    function ($q) use ($request) {
                        $q->whereHas('product', function ($sub) use ($request) {
                            $sub->where('type', $request->product_type);
                        });
                    }
                );
            }
        }

        $applications = $query->latest()->paginate(20)->withQueryString();

        // Get status options from enum
        $statusOptions = collect(ApprovalStatus::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ])->all();

        // Get product type options from enum
        $productTypeOptions = collect(ProductType::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => ucfirst(str_replace('_', ' ', $case->name)),
        ])->all();

        return Inertia::render('application/index', [
            'applications' => $applications,
            'statusOptions' => $statusOptions,
            'productTypeOptions' => $productTypeOptions,
        ]);
    }

    /* ===========================================================
     * Show the details of a specific application.
     */
    public function show(Application $application)
    {
        $application->load([
            'model',
            'model.member',
            'model.familyMembers',
            'model.grantors',
            'model.product',
        ]);

        //  dd($application->toArray());

        return Inertia::render('application/show', [
            'application' => $application,
        ]);
    }

    /* ===========================================================
     * Show the approval history of a specific application.
     */
    public function approvalHistory(Application $application)
    {
        $histories = $application->approvalHistories()
            ->with('approver')
            ->orderBy('approval_step')
            ->get();

        return response()->json([
            'histories' => $histories,
        ]);
    }

    /* ===========================================================
     * Handle approval action for an application.
    */
    public function approvalAction(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'remarks' => 'nullable|string',
            'document' => 'nullable|file',
        ]);

        $history = ApprovalHistory::where('application_id', $application->id)
            ->orderByDesc('id')
            ->first();

        $role = Auth::user()->roles[0]->name;

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('approval_documents', 'public');
        }

        switch (true) {
            case $history->approval_step == 1 && $role === 'loan committee member':
                return $this->handleStepOne($validated, $history, $role, $application, $documentPath, $request);

            case $history->approval_step == 2 && $role === 'loan committee secretary':
                return $this->handleStepTwo($validated, $history, $role, $application, $documentPath, $request);

            case $history->approval_step == 3 && $role === 'loan committee chairman':
                return $this->handleStepThree($validated, $history, $role, $application, $documentPath, $request);

            case $history->approval_step == 4 && $role === 'managing committee secretary':
                return $this->handleStepFour($validated, $history, $role, $application, $documentPath, $request);

            default:
                return response()->json(['error' => 'Invalid approval step or role.'], 422);
        }
    }

    // Step 1: loan committee member
    private function handleStepOne($validated, $history, $role, $application, $documentPath, $request)
    {
        $errorResponse = $this->validateApprovalStepOne($validated, $history, $role);
        if ($errorResponse) {
            return $errorResponse;
        }

        $this->updateHistory($history, $validated, $documentPath, $role);
        $step = $application->approval_step ?? 1;

        if ($validated['status'] === 'forwarded') {
            $step++;
            $this->forwardApplication($application, $step, 'loan committee secretary');
        } else {
            $this->updateApplicationStatus($application, $validated['status'], $role);
        }

        return response()->json(['success' => true]);
    }

    // Step 2: loan committee secretary
    private function handleStepTwo($validated, $history, $role, $application, $documentPath, $request)
    {
        $errorResponse = $this->validateApprovalStepOne($validated, $history, $role);
        if ($errorResponse) {
            return $errorResponse;
        }

        $this->updateHistory($history, $validated, $documentPath, $role);
        $step = $application->approval_step ?? 2;

        if ($validated['status'] === 'forwarded') {
            $step++;
            $this->forwardApplication($application, $step, 'loan committee chairman');
        } else {
            $this->updateApplicationStatus($application, $validated['status'], $role);
        }

        return response()->json(['success' => true]);
    }

    // Step 3: loan committee chairman
    private function handleStepThree($validated, $history, $role, $application, $documentPath, $request)
    {
        $errorResponse = $this->validateApprovalStepOne($validated, $history, $role);
        if ($errorResponse) {
            return $errorResponse;
        }

        $this->updateHistory($history, $validated, $documentPath, $role);
        $step = $application->approval_step ?? 3;

        if ($validated['status'] === 'forwarded') {
            $step++;
            $this->forwardApplication($application, $step, 'managing committee secretary');
        } else {
            $this->updateApplicationStatus($application, $validated['status'], $role);
        }

        return response()->json(['success' => true]);
    }

    // Step 4: managing committee secretary
    private function handleStepFour($validated, $history, $role, $application, $documentPath, $request)
    {
        $this->updateHistory($history, $validated, $documentPath, $role);

        if ($validated['status'] === 'forwarded') {
            return response()->json(['error' => 'You cannot forward the application at this step.'], 422);
        } else {
            $this->updateFinalApplicationStatus($application, $validated['status'], $role);
        }

        return response()->json(['success' => true]);
    }

    // Helper to update approval history
    private function updateHistory($history, $validated, $documentPath, $role)
    {
        $history->status = $validated['status'];
        $history->approval_role = $role;
        $history->remarks = $validated['remarks'] ?? $history->remarks;
        $history->document_path = $documentPath ?? $history->document_path;
        $history->approval_date = now();
        $history->approved_by = optional(Auth::user())->id;
        $history->approved_by_name = Auth::user()->name;
        $history->save();
    }

    // Helper to forward application to next step
    private function forwardApplication($application, $step, $nextRole)
    {
        $application->approval_step = $step;
        $application->role = $nextRole;
        $application->updated_by = optional(Auth::user())->id;
        $application->updated_by_name = Auth::user()->name ?? '';
        $application->updated_at = now();
        $application->save();

        ApprovalHistory::create([
            'application_id' => $application->id,
            'approval_step' => $step,
            'approval_role' => $nextRole,
            'status' => ApprovalStatus::PENDING->value,
            'created_at' => now(),
        ]);
    }

    // Helper to update application status (not final step)
    private function updateApplicationStatus($application, $status, $role)
    {
        $application->status = $status;
        $application->role = $role;
        $application->updated_by = optional(Auth::user())->id;
        $application->updated_by_name = Auth::user()->name ?? '';
        $application->updated_at = now();
        $application->save();
    }

    // Helper to update application status for final step
    private function updateFinalApplicationStatus($application, $status, $role)
    {
        $application->status = $status;
        $application->role = $role;

        if ($status === ApprovalStatus::APPROVED->value) {
            $application->is_approved = true;
            $application->notes = 'Loan application arroved by the committee';
            $application->approval_date = now();
            $application->approved_by = optional(Auth::user())->id;
            $application->approved_by_name = Auth::user()->name ?? '';

            // Generate loan schedule if not already generated
            if ($application->model_type === Loan::class) {
                $loan = $application->model;
                if ($loan && $loan->loan_schedules()->count() === 0) {
                    $this->generateLoanSchedule($loan, $application);
                }
            }
        } elseif ($status === ApprovalStatus::REJECTED->value) {
            $application->is_rejected = true;
            $application->rejection_date = now();
            $application->rejected_by = optional(Auth::user())->id;
            $application->rejected_by_name = Auth::user()->name ?? '';
        }

        $application->updated_by = optional(Auth::user())->id;
        $application->updated_by_name = Auth::user()->name ?? '';
        $application->updated_at = now();
        $application->save();
    }

    // Add this method to the controller:
    private function generateLoanSchedule($loan, $application)
    {
        // Assumes $loan has: amount, interest_rate (annual, percent), term (months), start_date
        $principal = $loan->loan_amount ?? $loan->amount ?? 0;
        $rate = $loan->interest_rate ?? 0; // annual rate in percent
        $term = $loan->loan_term_months ?? $loan->term ?? $loan->total_installment ?? 0; // in months
        $startDate = $loan->installment_start_date ?? $loan->start_date ?? now();

        // Log the values for debugging
        \Log::info('Loan Schedule Generation', [
            'principal' => $principal,
            'rate' => $rate,
            'term' => $term,
            'startDate' => $startDate,
            'loan_id' => $loan->id ?? null,
            'application_id' => $application->id ?? null,
        ]);

        // Prevent division by zero
        if (!$term || $term <= 0) {
            return;
        }

        $monthlyPrincipal = round($principal / $term, 2);
        $monthlyInterest = round(($principal * ($rate / 100)) / 12, 2);

        for ($i = 1; $i <= $term; $i++) {
            $dueDate = \Carbon\Carbon::parse($startDate)->addMonths($i);
            \App\Models\LoanSchedule::create([
                'loan_id' => $loan->id,
                'application_id' => $application->id,
                'installment_number' => $i,
                'due_date' => $dueDate,
                'principal' => $monthlyPrincipal,
                'interest' => $monthlyInterest,
                'total_payment' => $monthlyPrincipal + $monthlyInterest,
                'paid' => false,
            ]);
        }
    }

    /* ===========================================================
     * Validate approval for step one.
     */
    private function validateApprovalStepOne($validated, $history, $role)
    {

        if ($validated['status'] === $history->status && $role === $history->approval_role) {
            return response()->json(['error' => 'You cannot approve or forward the application with the same status.'], 422);
        }

        if ($history && $history->status === ApprovalStatus::APPROVED->value && $validated['status'] !== ApprovalStatus::FORWARDED->value) {
            return response()->json(['error' => 'You cannot change the status after it has been approved.'], 422);
        }

        return null; // No error
    }
}
