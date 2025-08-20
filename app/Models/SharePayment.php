<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePayment extends Model
{
    use HasFactory;

    protected $table = 'share_payments';

    protected $fillable = [
        'share_acc_id',
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

    public function shareAccount()
    {
        return $this->belongsTo(ShareAccount::class, 'share_acc_id', 'id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
