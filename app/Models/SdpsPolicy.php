<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SdpsPolicy extends Model
{
    use HasFactory;

    protected $table = 'sdps_policies';

    protected $fillable = [
        'monthly_installment',
        'maturity_period',
        'after_machurity_pay',
        'bonus',
        'created_by',
        'updated_by',
    ];
}
