<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisions'; // Ensure this matches your database table name
    protected $primaryKey = 'id'; // Ensure this matches your primary key column

    protected $fillable = [
        'division_name_en',
        'division_name_bn',
    ];
}
