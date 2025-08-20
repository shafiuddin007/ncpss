<?php

namespace App\Http\Controllers;

use App\Models\SavingsAccount;
use App\Models\Member;
use App\Models\Nominee;
use Inertia\Inertia;
use App\Enums\Relationship;
use App\Models\SavingsPayment;
use Illuminate\Http\Request;
use App\Services\ApprovalService;
use App\Enums\ApprovalStatus;
use App\Models\ApprovalHistory;
use Illuminate\Support\Facades\DB;
use App\Models\Application;

class SavingsAccountController extends Controller
{
    public function index()
    {
        $savingsAccounts = SavingsAccount::with(['member', 'nominee'])->get();
        $relationshipOptions = array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            Relationship::cases()
        );
        return Inertia::render('SavingsAccounts/Index', [
            'savingsAccounts' => $savingsAccounts,
            'relationshipOptions' => $relationshipOptions,
        ]);
    }

    public function create()
    {
        $relationshipOptions = array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            Relationship::cases()
        );
        return Inertia::render('SavingsAccounts/Create', [
            'relationshipOptions' => $relationshipOptions,
        ]);
    }

    public function store(Request $request, ApprovalService $approvalService)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'savings_account_number' => 'required|string|max:255',
            'initial_deposit' => 'required|numeric|min:0',
            // Nominee fields validation
            'nominee_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'contact_no' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'scan_image' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        DB::beginTransaction();
        try {
            $scanImagePath = null;
            if ($request->hasFile('scan_image')) {
                $scanImagePath = $request->file('scan_image')->store('nominee_scans', 'public');
            }

            $nominee = Nominee::create([
                'member_id' => $validated['member_id'],
                'model_type' => SavingsAccount::class,
                'nid_birth_no' => '', // Set as needed or add to form/validation
                'nominee_name' => $validated['nominee_name'],
                'relationship' => $validated['relationship'],
                'age' => $validated['age'],
                'contact_no' => $validated['contact_no'],
                'address' => $validated['address'] ?? null,
                'scan_image' => $scanImagePath,
            ]);

            $savingsAccount = SavingsAccount::create([
                'member_id' => $validated['member_id'],
                'savings_account_number' => $validated['savings_account_number'],
                'initial_deposit' => $validated['initial_deposit'],
                'nominee_id' => $nominee->id,
                'status' => ApprovalStatus::PENDING->value,
            ]);

            $application = Application::create([
                'model_type' => SavingsAccount::class,
                'model_id' => $savingsAccount->id,
                'member_id' => $validated['member_id'],
                'status' => ApprovalStatus::PENDING->value,
                'approval_step' => 1,
                'role' => 'Secretary',
                'created_by' => auth()->user()->id ?? null,
                'created_by_name' => auth()->user()->name ?? null,
                'application_date' => now(),
            ]);

            $documentPath = null;

            $approvalService->createApprovalHistory([
                'application_id' => $application->id,
                'application_type' => SavingsAccount::class,
                'approval_step' => 1,
                'approval_role' => 'Secretary',
                'status' => ApprovalStatus::FORWARDED->value,
                'created_by' => auth()->user()->id ?? null,
                'created_by_name' => auth()->user()->name ?? null,
            ], $documentPath);

            if ($savingsAccount->initial_deposit > 0) {
                SavingsPayment::create([
                    'savings_acc_id' => $savingsAccount->id,
                    'member_id' => $savingsAccount->member_id,
                    'year' => now()->year,
                    'month' => now()->month,
                    'due_date' => now()->toDateString(),
                    'amount' => $savingsAccount->initial_deposit,
                    'due' => 0,
                    'payment_date' => now()->toDateString(),
                    'is_paid' => true,
                    'created_by' => auth()->user()->email ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('savings-accounts.index')->with('success', 'Savings account request submitted and pending approval.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create savings account: ' . $e->getMessage());
        }
    }

    public function savings_application($memberId)
    {
        $member = Member::findOrFail($memberId);

        $savingsAccount = $member->savingsAccount()->first();

        $relationshipOptions = array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            Relationship::cases()
        );

        return Inertia::render('SavingsAccounts/Create', [
            'member' => $member,
            'relationshipOptions' => $relationshipOptions,
            'savingsAccount' => $savingsAccount,
        ]);
    }
}
