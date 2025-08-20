<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsAccount extends Model
{
    use HasFactory;

    protected $table = 'savings_accounts';

    protected $fillable = [
        'member_id',
        'savings_account_number',
        'initial_deposit',
        'nominee_id',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function nominee()
    {
        return $this->belongsTo(Nominee::class, 'nominee_id', 'id');
    }
}
