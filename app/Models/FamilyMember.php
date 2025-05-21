<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FamilyMember extends Model
{
    use HasFactory;

    protected $table = 'family_members';

    protected $fillable = [
        'loan_id',
        'member_id',
        'relation',
        'current_deposit',
        'current_loan',
        'signature_path',
    ];

    /**
     * Get the loan associated with the family member.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the member associated with the family member.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }


    public function getSignatureUrlAttribute(): ?string
    {
        if (!$this->signature_path) {
            return null;
        }

        if (filter_var($this->signature_path, FILTER_VALIDATE_URL)) {
            return $this->signature_path;
        }

        return Storage::exists($this->signature_path) 
            ? Storage::url($this->signature_path)
            : null;
    }

    public function setSignaturePathAttribute($value): void
    {
        if (empty($value)) {
            $this->attributes['signature_path'] = null;
            return;
        }

        // Remove any existing storage path prefix to prevent duplication
        $this->attributes['signature_path'] = str_replace(
            ['storage/', 'public/'],
            '',
            $value
        );
    }
}