<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Introducer extends Model
{
    use HasFactory;

    protected $table = 'introducers';

    protected $fillable = [
        'name',
        'account_number',
        'signature',
        'date',
    ];

    /**
     * Define the relationship with the Member model.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}