<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'application_id',
        'installment_number',
        'due_date',
        'principal',
        'interest',
        'total_payment',
        'paid',
        'paid_date',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
