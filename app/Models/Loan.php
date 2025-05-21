<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'loan_amount',
        'loan_purpose',
        'loan_type',
        'uregent_fee',
        'total_installment',
        'installment_start_date',
        'first_installment',
        'other_loan_amount',
        'other_loan_installment',
        'other_loan_remaining',
        'loan_collateral_type',
        'self_deposite_amount',
        'family_member',
        'status',
        'is_active',
        'is_delete',
    ];

    protected $attributes = [
        'interest_rate' => 0,
        'min_balance' => 0,
        'max_loan_amount' => 0,
        'loan_term_months' => 0,
        'self_income' => 0,
        'family_income' => 0,
        'total_income' => 0,
        'rent' => 0,
        'food_expense' => 0,
        'education_expense' => 0,
        'transport_expense' => 0,
        'other_expense' => 0,
        'total_expense' => 0,
        'loan_amount' => 0,
        'uregent_fee' => 0,
        'total_installment' => 0,
        'other_loan_amount' => 0,
        'other_loan_installment' => 0,
        'other_loan_remaining' => 0,
        'family_member' => 0,
        'status' => 'pending',
        'is_active' => true,
        'is_delete' => false,
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

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function grantors()
    {
        return $this->hasMany(Grantor::class);
    }
}
