<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nominee extends Model
{
    use HasFactory;
    
    protected $table = 'nominees';

    protected $fillable = [
        'member_id',
        'nid_birth_no',
        'nominee_name',
        'relationship',
        'age',
        'contact_no',
        'address',
    ];

    /**
     * Define the relationship with the Member model.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}