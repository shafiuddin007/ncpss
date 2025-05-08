<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // Table name

    protected $fillable = [
        'name',
        'type',
        'description',
        'interest_rate',
        'min_balance',
        'max_loan_amount',
        'loan_term_months',
        'currency',
        'is_active',
    ];
}
