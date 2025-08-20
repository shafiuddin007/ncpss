<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyCloser extends Model
{
    use HasFactory;

    // Make sure the table name matches your actual table name in the database
    protected $table = 'monthly_closures'; // <-- changed from 'monthly_closers'

    protected $fillable = [
        'month',
        'year',
        'closed_at',
        'closed_by',
        'notes',
    ];

    protected $dates = [
        'closed_at',
    ];

    /**
     * Optionally, define relationship to User if you want to track who closed the month.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'closed_by');
    }
}
