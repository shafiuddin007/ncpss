<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;
    protected $table = 'applications';

    protected $fillable = [
        'model_id',
        'model_type',
        'application_number',
        'approval_step',
        'status',
        'notes',
        'is_approved',
        'is_rejected',
        'application_date',
        'approval_date',
        'rejection_date',
        'approved_by',
        'approved_by_name',
        'rejected_by',
        'rejected_by_name',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
        'deleted_by',
        'deleted_by_name',
        'is_deleted',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->application_number = self::generateApplicationNumber($model->model_id);
        });
    }

    public static function generateApplicationNumber($modelId)
    {
        $prefix = 'APP-';
        $timestamp = now()->format('Ymd');
        $suffix = str_pad($modelId, 6, '0', STR_PAD_LEFT);

        return "{$prefix}{$timestamp}-{$suffix}";
    }

    // Polymorphic relation
    public function model()
    {
        return $this->morphTo();
    }

    // Optionally, relations to users
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
