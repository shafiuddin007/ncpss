<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'share_account_number',
        'balance',
        'employer_name',
        'employer_address',
        'employer_email',
        'employer_phone',
        'nominee_id',
    ];

    // Relationships (optional)
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function nominee()
    {
        return $this->belongsTo(Nominee::class);
    }
}
