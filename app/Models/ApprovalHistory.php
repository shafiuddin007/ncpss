<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalHistory extends Model
{
    use SoftDeletes;
    protected $table = 'approval_histories';

    protected $fillable = [
        'application_id',
        'approval_step',
        'approval_role',
        'status',
        'remarks',
        'document_path',
        'approval_date',
        'approved_by',
        'approved_by_name',
        'is_deleted',
        'created_by',
        'created_by_name',
    ];

    // Relationships
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}