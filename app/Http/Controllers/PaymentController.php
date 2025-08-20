<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Loan;
use App\Models\LoanSchedule;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ShareAccount;
use App\Models\SharePayment;

class PaymentController extends Controller
{
    public function loanRepayment($memberId, Request $request)
    {
        $member = Member::findOrFail($memberId);

        // Fetch active loan for the member
        $loan = Loan::where('member_id', $member->id)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->latest()
            ->first();

        //dd($member, $loan);

        $loanSchedule = null;
        if ($loan) {
            // Get current month due schedule
            $loanSchedule = LoanSchedule::where('loan_id', $loan->id)
                ->whereDate('due_date', '>=', Carbon::now()->startOfMonth())
                ->whereDate('due_date', '<=', Carbon::now()->endOfMonth())
                ->where('paid', false)
                ->orderBy('due_date')
                ->first();
        }

        // Fetch active share account for the member
        $shareAccount = ShareAccount::where('member_id', $member->id)
            ->where('status', 'active')
            ->first();

        // Fetch share payment for current month and year for this share account
        $sharePayment = null;
        if ($shareAccount) {
            $sharePayment = SharePayment::where('share_acc_id', $shareAccount->id)
                ->where('year', now()->year)
                ->where('month', now()->month)
                ->first();
        }

        return Inertia::render('member/Payment', [
            'member' => $member,
            'loan' => $loan,
            'loanSchedule' => $loanSchedule,
            'shareAccount' => $shareAccount,
            'sharePayment' => $sharePayment,
        ]);
    }

    public function loanSchedulePayment(Request $request, $loanScheduleId)
    {
        $loanSchedule = LoanSchedule::findOrFail($loanScheduleId);

        $loanSchedule->paid = true;
        $loanSchedule->paid_date = now();
        $loanSchedule->payment_by = Auth::user()->name ?? 'System';
        $loanSchedule->save();

        // Redirect to confirmation page with member and payment info
        return redirect()->route('payment.confirmation', [
            'member' => $loanSchedule->loan->member_id,
            'loanSchedule' => $loanSchedule->id,
        ]);
    }

    public function paymentConfirmation($memberId, $loanScheduleId)
    {
        $member = Member::findOrFail($memberId);
        $loanSchedule = LoanSchedule::findOrFail($loanScheduleId);

        return Inertia::render('member/PaymentConfirmation', [
            'member' => $member,
            'loanSchedule' => $loanSchedule,
        ]);
    }


        public function showLoanSchedule($memberId)
        {
            $member = Member::findOrFail($memberId);

            $loan = Loan::where('member_id', $member->id)
                ->where('status', 'approved')
                ->where('is_active', true)
                ->latest()
                ->first();

            $schedules = [];
            if ($loan) {
                $schedules = LoanSchedule::where('loan_id', $loan->id)
                    ->orderBy('due_date')
                    ->get(['id', 'due_date', 'total_payment', 'paid', 'paid_date']);
            }

            return Inertia::render('member/LoanSchedule', [
                'member' => $member,
                'loanSchedules' => $schedules,
            ]);
        }
    }
