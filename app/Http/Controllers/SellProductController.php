<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatus;
use App\Models\ApprovalHistory;
use App\Models\FamilyMember;
use App\Models\Grantor;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Js;
use App\Enums\Status;
use App\Enums\ProductType;
use App\Models\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SellProductController extends Controller
{

    public function sellProduct($memberId)
    {
        $member = Member::findOrFail($memberId);
        $products = Product::where('is_active', true)
            ->get(['id', 'name', 'min_balance', 'max_loan_amount', 'interest_rate', 'loan_term_months']);
        return Inertia::render('sell-product/sell-product', [
            'member' => $member,
            'products' => $products,
            'loan_info' => array('current_share_amount' => '100000', 'share_b4_3m' => '60000', 'previous_loan' => '50000'),
        ]);
    }


    public function storeSoldProduct(Request $request, $memberId): JsonResponse|RedirectResponse
    {
        try {
            DB::beginTransaction();
            $product = Product::findOrFail($request->input('product_id'));
            if ($product->type == ProductType::LOAN) {

                $loanId = $this->storeLoanProduct($request, $memberId);

                if (!$loanId) {
                    // Error from storeLoanProduct
                    return redirect()->back()->withErrors(['application' => 'Failed to create loan.'])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['application' => 'Selected product is not a loan.'])->withInput();
            }

            //================== Insert into application table ==============
            $application = Application::create([
                'model_id' => $loanId,
                'model_type' => Loan::class,
                'application_number' => Application::generateApplicationNumber($loanId),
                'approval_step' => 1,
                'status' => ApprovalStatus::PENDING->value,
                'role' => 'loan committee member',
                'notes' => 'Application submitted successfully and waiting for Approval',
                'application_date' => now(),
                'created_by' => Auth::id(),
                'created_by_name' => Auth::user()->name,
            ]);
            //================== Insert into approval_histories table ==================
            ApprovalHistory::create([
                'application_id' => $application->id,
                'approval_step' => 1,
                'approval_role' => 'loan committee member',
                'status' => ApprovalStatus::PENDING->value,
                'remarks' => 'Application submitted successfully and waiting for Approval',
                'document_path' => null,
                'created_by' => Auth::id(),
                'created_by_name' => Auth::user()->name,
            ]);

            DB::commit();
            return redirect()->route('members.sell-product', ['member' => $memberId])
                ->with('success', 'Application successfully submitted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['application' => $e->getMessage()])
                ->withInput();
        }


        return redirect()->back()->withErrors(['product' => 'Selected product is not a loan.'])->withInput();
    }

    protected function storeLoanProduct(Request $request, $memberId)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'interest_rate' => 'required|numeric|min:0|max:100',
                'min_balance' => 'nullable|numeric|min:0',
                'max_loan_amount' => 'nullable|numeric|min:0',
                'loan_term_months' => 'required|integer|min:0',

                'office_address' => 'required|string',
                'occupation' => 'nullable|string',
                'designation' => 'nullable|string',
                'office_contact' => 'required|string',

                'self_income' => 'required|numeric',
                'family_income' => 'nullable|numeric',
                'total_income' => 'required|numeric',

                'rent' => 'required|numeric',
                'food_expense' => 'required|numeric',
                'education_expense' => 'nullable|numeric',
                'transport_expense' => 'nullable|numeric',
                'other_expense' => 'nullable|numeric',
                'total_expense' => 'required|numeric',

                'loan_amount' => 'required|numeric|min:1',
                'loan_purpose' => 'required|string',
                'loan_type' => 'nullable|string',
                'urgent_fee' => 'nullable|numeric',

                'total_installment' => 'required|numeric',
                'start_date' => 'nullable|date',

                'other_loan_amount' => 'nullable|numeric',
                'other_loan_installment' => 'nullable|numeric',
                'other_loan_remaining' => 'nullable|numeric',

                'loan_surety_type' => 'required|string',
                'self_deposit_amount' => 'nullable|numeric',
                'family_member' => 'required|integer',

                'grantors' => 'array',
                'grantors.*.member_id' => 'nullable|string',
                'grantors.*.deposit_amount' => 'nullable|numeric',
                'grantors.*.loan_amount' => 'nullable|numeric',

                'family_members' => 'array',
                'family_members.*.relation' => 'nullable|string',
                'family_members.*.member_id' => 'nullable|string',
                'family_members.*.current_deposit' => 'nullable|numeric',
                'family_members.*.current_loan' => 'nullable|numeric',
            ]);

            DB::beginTransaction();

            $loan = Loan::create([
                'member_id' => $memberId,
                'product_id' => $validated['product_id'],
                'interest_rate' => $validated['interest_rate'],
                'min_balance' => $validated['min_balance'],
                'max_loan_amount' => $validated['max_loan_amount'],
                'loan_term_months' => $validated['loan_term_months'],

                'office_address' => $validated['office_address'],
                'occupation' => $validated['occupation'],
                'designation' => $validated['designation'],
                'office_contact' => $validated['office_contact'],

                'self_income' => $validated['self_income'] ?? 0,
                'family_income' => $validated['family_income'] ?? 0,
                'total_income' => $validated['total_income'] ?? 0,

                'rent' => $validated['rent'] ?? 0,
                'food_expense' => $validated['food_expense'] ?? 0,
                'education_expense' => $validated['education_expense'] ?? 0,
                'transport_expense' => $validated['transport_expense'] ?? 0,
                'other_expense' => $validated['other_expense'] ?? 0,
                'total_expense' => $validated['total_expense'] ?? 0,

                'loan_amount' => $validated['loan_amount'],
                'loan_purpose' => $validated['loan_purpose'],
                'loan_type' => $validated['loan_type'],
                'uregent_fee' => $request->input('loan_type') === 'Urgent' ? $request->input('urgent_fee') : 0,

                'total_installment' => $validated['total_installment'],
                'installment_start_date' => $validated['start_date'],

                'other_loan_amount' => $validated['other_loan_amount'] ?? 0,
                'other_loan_installment' => $validated['other_loan_installment'] ?? 0,
                'other_loan_remaining' => $validated['other_loan_remaining'] ?? 0,

                'loan_collateral_type' => $validated['loan_surety_type'],
                'self_deposite_amount' => $validated['self_deposit_amount'] ?? null,
                'family_member' => $validated['family_member'],
                'status' => Status::WAITING_FOR_APPROVAL->value,
                'is_active' => false,
                'is_delete' => false,
            ]);

            // Save grantors if present
            if (!empty($validated['grantors'])) {
                foreach ($validated['grantors'] as $grantorData) {
                    Grantor::create([
                        'loan_id' => $loan->id,
                        'grantor_member_id' => $grantorData['member_id'] ?? null,
                        'deposit_amount' => $grantorData['deposit_amount'] ?? null,
                        'loan_amount' => $grantorData['loan_amount'] ?? null,
                    ]);
                }
            }

            // Save family members if present
            if (!empty($validated['family_members'])) {
                foreach ($validated['family_members'] as $memberData) {
                    FamilyMember::create([
                        'loan_id' => $loan->id,
                        'relation' => $memberData['relation'] ?? null,
                        'member_id' => $memberData['member_id'] ?? null,
                        'current_deposit' => $memberData['current_deposit'] ?? null,
                        'current_loan' => $memberData['current_loan'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return $loan->id;
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            // Combine all validation messages into one string
            $message = collect($e->errors())->flatten()->join(' ');
            throw new \Exception($message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['application' => $e->getMessage()])
                ->withInput();
        }
    }
}
