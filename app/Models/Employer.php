<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $table = 'employers';

    protected $fillable = [
        // 'member_id', // removed, not present in DB
        'name',
        'email',
        'phone',
        'designation',
        'address',
        'is_active',
        'is_deleted',
    ];

    // Remove the member() relationship if member_id does not exist
    // public function member()
    // {
    //     return $this->belongsTo(Member::class, 'member_id', 'id');
    // }
}
