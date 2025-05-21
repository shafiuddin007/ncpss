<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Grantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'grantor_member_id',
        'deposit_amount',
        'loan_amount',
        'document_path',
    ];

    protected $appends = ['document_url'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'grantor_member_id');
    }

    public function getDocumentUrlAttribute(): ?string
    {
        if (!$this->document_path) {
            return null;
        }

        if (filter_var($this->document_path, FILTER_VALIDATE_URL)) {
            return $this->document_path;
        }

        return Storage::exists($this->document_path) 
            ? Storage::url($this->document_path)
            : null;
    }

     public function setDocumentPathAttribute($value): void
    {
        if (empty($value)) {
            $this->attributes['document_path'] = null;
            return;
        }

        // Remove any existing storage path prefix to prevent duplication
        $this->attributes['document_path'] = str_replace(
            ['storage/', 'public/'],
            '',
            $value
        );
    }

}
