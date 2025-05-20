<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_number',
        'loan_number',
        'member_id',
        'product_id',
        'interest_rate',
        'min_balance',
        'max_loan_amount',
        'loan_term_months',
        'office_address',
        'occupation',
        'designation',
        'office_contact',
        'self_income',
        'family_income',
        'total_income',
        'rent',
        'food_expense',
        'education_expense',
        'transport_expense',
        'other_expense',
        'total_expense',
        'current_share_amount',
        'before_share_amount',
        'loan_amount',
        'loan_type',
        'loan_purpose',
        'previous_loans',
        'is_reg_paid',
        'total_installment',
        'first_installment',
        'other_loan_amount',
        'other_loan_installment',
        'other_loan_remaining',
        'loan_surety_id',
        'surety_name',
        'self_surety_amount',
        'start_date',
        'end_date',
        'status',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}