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
        // ============================= APPROVAL LOGIC ==============================
        if ($history->approval_step == 1 && $role === 'loan committee member') {
            $errorResponse = $this->validateApprovalStepOne($validated, $history, $role);
            if ($errorResponse) {
                return $errorResponse;
            }


            // Update the current history record
            $history->status = $validated['status'];
            $history->remarks = $validated['remarks'] ?? $history->remarks;
            $history->document_path = $request->hasFile('document') ?  $documentPath : $history->document_path;
            $history->approval_date = now();
            $history->approved_by = optional(Auth::user())->id;
            $history->approved_by_name = Auth::user()->name;
            $history->save();

            //Update application status and approval step    
            $step = $application->approval_step ?? 1;
            if ($validated['status'] === 'forwarded') {
                $step++;
                $application->approval_step = $step;
                $application->role = 'loan committee secretary';
                $application->updated_by = optional(Auth::user())->id;
                $application->updated_by_name = Auth::user()->name ?? '';
                $application->updated_at = now();
                $application->save();

                ApprovalHistory::create([
                    'application_id' => $application->id,
                    'approval_step' => $step,
                    'approval_role' => 'loan committee secretary',
                    'status' => ApprovalStatus::PENDING->value,
                    'created_at' => now(),
                ]);
            } else {
                $application->status = $validated['status'];
                $application->role = $role;
                $application->updated_by = optional(Auth::user())->id;
                $application->updated_by_name = Auth::user()->name ?? '';
                $application->updated_at = now();
                $application->save();
            }

            return response()->json(['success' => true]);
        } elseif ($history->approval_step == 2 && $role === 'loan committee secretary') {
            // Validate the status change
            $errorResponse = $this->validateApprovalStepOne($validated, $history, $role);
            if ($errorResponse) {
                return $errorResponse;
            }

            // Update the current history record
            $history->status = $validated['status'];
            $history->remarks = $validated['remarks'] ?? $history->remarks;
            $history->document_path = $request->hasFile('document') ?  $documentPath : $history->document_path;
            $history->approval_date = now();
            $history->approved_by = optional(Auth::user())->id;
            $history->approved_by_name = Auth::user()->name;
            $history->save();

            //Update application status and approval step 
            $step = $application->approval_step ?? 2;
            if ($validated['status'] === 'forwarded') {
                $step++;
                $application->approval_step = $step;
                $application->role = 'loan committee chairman';
                $application->updated_by = optional(Auth::user())->id;
                $application->updated_by_name = Auth::user()->name ?? '';
                $application->updated_at = now();
                $application->save();

                ApprovalHistory::create([
                    'application_id' => $application->id,
                    'approval_step' => $step,
                    'approval_role' => 'loan committee chairman',
                    'status' => ApprovalStatus::PENDING->value,
                    'created_at' => now(),
                ]);
            } else {
                // If not forwarded, update the application status
                $application->status = $validated['status'];
                $application->role = 'loan committee secretary';
                $application->updated_by = optional(Auth::user())->id;
                $application->updated_by_name = Auth::user()->name ?? '';
                $application->updated_at = now();
                $application->save();
            }
        } elseif ($history->approval_step == 3 && $role === 'loan committee chairman') {
            // Validate the status change
            $errorResponse = $this->validateApprovalStepOne($validated, $history, $role);
            if ($errorResponse) {
                return $errorResponse;
            }

            // Update the current history record
            $history->status = $validated['status'];
            $history->remarks = $validated['remarks'] ?? $history->remarks;
            $history->document_path = $request->hasFile('document') ?  $documentPath : $history->document_path;
            $history->approval_date = now();
            $history->approved_by = optional(Auth::user())->id;
            $history->approved_by_name = Auth::user()->name;
            $history->save();

            //Update application status and approval step 
            $step = $application->approval_step ?? 3;
            if ($validated['status'] === 'forwarded') {
                $step++;
                $application->approval_step = $step;
                $application->role = 'managing committee secretary';
                $application->updated_by = optional(Auth::user())->id;
                $application->updated_by_name = Auth::user()->name ?? '';
                $application->updated_at = now();
                $application->save();

                ApprovalHistory::create([
                    'application_id' => $application->id,
                    'approval_step' => $step,
                    'approval_role' => 'managing committee secretary',
                    'status' => ApprovalStatus::PENDING->value,
                    'created_at' => now(),
                ]);
            } else {
                // If not forwarded, update the application status
                $application->status = $validated['status'];
                $application->role = 'loan committee chairman';
                $application->updated_by = optional(Auth::user())->id;
                $application->updated_by_name = Auth::user()->name ?? '';
                $application->updated_at = now();
                $application->save();
            }
        } elseif ($history->approval_step == 4 && $role === 'managing committee secretary') {
            // Validate the status change
            // $errorResponse = $this->validateApprovalStepOne($validated, $history, $role);
            // if ($errorResponse) {
            //     return $errorResponse;
            // }

            // Update the current history record
            $history->status = $validated['status'];
            $history->remarks = $validated['remarks'] ?? $history->remarks;
            $history->document_path = $request->hasFile('document') ?  $documentPath : $history->document_path;
            $history->approval_date = now();
            $history->approved_by = optional(Auth::user())->id;
            $history->approved_by_name = Auth::user()->name;
            $history->save();

            //Update application status and approval step 
            if ($validated['status'] === 'forwarded') {
                return response()->json(['error' => 'You cannot forward the application at this step.'], 422);
            } else {
                // If not forwarded, update the application status
                $application->status = $validated['status'];
                $application->role = 'managing committee secretary';
                if ($validated['status'] === ApprovalStatus::APPROVED->value) {
                    $application->is_approved = true;
                    $application->approval_date = now();
                    $application->approved_by = optional(Auth::user())->id;
                    $application->approved_by_name = Auth::user()->name ?? '';
                } elseif ($validated['status'] === ApprovalStatus::REJECTED->value) {
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
        } else {
            return response()->json(['error' => 'Invalid approval step or role.'], 422);
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
        // if ($validated['status'] === ApprovalStatus::REJECTED->value && $role !== 'admin') {
        //     return response()->json(['error' => 'Only admin can reject an application.'], 422);
        // }

        // if ($validated['status'] === ApprovalStatus::APPROVED->value && $role !== 'admin') {
        //     return response()->json(['error' => 'Only admin can approve an application.'], 422);
        // }

        return null; // No error
    }
}
