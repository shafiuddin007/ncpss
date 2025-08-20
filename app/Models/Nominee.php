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
        'model_type', // Added to specify the model type
        'nid_birth_no',
        'nominee_name',
        'relationship',
        'age',
        'contact_no',
        'address',
        'scan_image',
    ];

    /**
     * Define the relationship with the Member model.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}