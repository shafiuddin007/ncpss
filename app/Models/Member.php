<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = [
        'name',
        'father_name',
        'mother_name',
        'gender',
        'nid',
        'dob',
        'nationality',
        'religion',
        'place_of_birth',
        'marital_status',
        'occupation',
        'blood_group',
        'photo',
        'signature',
        'educational_level',
        'mobile',
        'email',
        'pre_address',
        'pre_division',
        'pre_district',
        'pre_thana',
        'pre_union',
        'pre_post_code',
        'per_address',
        'per_division',
        'per_district',
        'per_thana',
        'per_union',
        'per_post_code',
        'status',
        'created_by',
        'updated_by',
        'is_active',
        'is_deleted',
        'previous_member_number',
        'is_previous_member',
    ];
    
    protected $with = [
        'introducer',
        'nominees',
        'country',
        'preDivision',
        'preDistrict',
        'preThana',
        'perDivision',
        'perDistrict',
        'perThana',
    ];
    /**
     * Define the relationship with the Nominee model.
     */
    public function nominees()
    {
        return $this->hasOne(Nominee::class);
    }

    /**
     * Define the relationship with the Member model for previous members.
     */
    public function previousMember()
    {
        return $this->belongsTo(Member::class, 'previous_member_number');
    }

    /**
     * Define the relationship with the Introducer model.
     */
    public function introducer()
    {
        return $this->hasOne(Introducer::class, 'member_id', 'id');
    }

    /**
     * Define the relationship with the Country model.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'nationality', 'id'); // Assuming 'nationality' is the foreign key
    }

    /**
     * Define the relationship with the Division model for present address.
     */
    public function preDivision()
    {
        return $this->belongsTo(Division::class, 'pre_division', 'id');
    }

    /**
     * Define the relationship with the District model for present address.
     */
    public function preDistrict()
    {
        return $this->belongsTo(District::class, 'pre_district', 'id');
    }

    /**
     * Define the relationship with the Thana model for present address.
     */
    public function preThana()
    {
        return $this->belongsTo(Thana::class, 'pre_thana', 'id');
    }

    /**
     * Define the relationship with the Division model for permanent address.
     */
    public function perDivision()
    {
        return $this->belongsTo(Division::class, 'per_division', 'id');
    }

    /**
     * Define the relationship with the District model for permanent address.
     */
    public function perDistrict()
    {
        return $this->belongsTo(District::class, 'per_district', 'id');
    }

    /**
     * Define the relationship with the Thana model for permanent address.
     */
    public function perThana()
    {
        return $this->belongsTo(Thana::class, 'per_thana', 'id');
    }
}
