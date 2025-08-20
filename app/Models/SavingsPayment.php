<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsPayment extends Model
{
    use HasFactory;

    protected $table = 'savings_payments';

    protected $fillable = [
        'savings_acc_id',
        'member_id',
        'year',
        'month',
        'due_date',
        'amount',
        'due',
        'payment_date',
        'is_paid',
        'created_by',
    ];

    public function savingsAccount()
    {
        return $this->belongsTo(SavingsAccount::class, 'savings_acc_id', 'id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
