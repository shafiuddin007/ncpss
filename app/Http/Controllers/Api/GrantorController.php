<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Grantor;
use App\Models\Member;
use App\Models\LoanSchedule;

use Carbon\Carbon;
class GrantorController extends Controller
{
    public function findGrantor(Request $request, $grantor_id): JsonResponse
    {
        $applicant_id = $request->query('applicant_id');

        $grantor = Member::where('id', $grantor_id)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->first();

        $errorResponse = $this->validateGrantor($grantor_id, $applicant_id);

        // If validation fails, return the error response
        if ($errorResponse) {
            return $errorResponse;
        }

        return response()->json($grantor);
    }





    private function validateGrantor($grantor_id, $applicantId)
    {

        $crossGrantor = Grantor::where('grantor_member_id', $applicantId)
            ->where('applicant_id', $grantor_id)
            ->where('status', 'active')
            ->get();

        // If there are cross grantors, return an error response
        if ($crossGrantor->isNotEmpty()) {
            return response()->json(['error' => 'Cross grantor found.'], 422);
        }

       

        $loan = Loan::where('member_id', $grantor_id)
            ->where('status', 'approved')
            ->first();

        // If no loan is found, return null
        if (!$loan) {
            return null;
        }


        // Get the last paid installment

        $installment = LoanSchedule::where('loan_id', $loan->id)
            ->where('paid', true)
            ->orderByDesc('installment_number')
            ->first();

        // Has loan but If no paid installment is found, return an error response

        if (!$installment) {
            return response()->json(['error' => 'No paid installments found for this shurity.'], 422);
        }

        $paidDate = Carbon::parse($installment->paid_date);
        $lastMonth = now()->subMonth();


        // Check if the paid date is in the last month
        $isSameMonthAndYear = $paidDate->month === $lastMonth->month && $paidDate->year === $lastMonth->year;

        // Check if the installment number is 1 and the paid date is from the last month
        // or if the installment number is greater than 1 and the paid date is from the last month
        // If either condition is true, return null (indicating no error)
        // Otherwise, return an error response

        if (
            ($installment->installment_number == 1 && ($paidDate->month >= $lastMonth->month && $paidDate->year === $lastMonth->year))
            || ($installment->installment_number > 1 && $isSameMonthAndYear)
        ) {
            return null;
        }
        // If the conditions are not met, return an error response
        // indicating that defaulted installments were found

        return response()->json(['error' => 'Unpaid installments found.'], 422);
    }

}

