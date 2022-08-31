<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerHistory extends Model
{
    protected $table = 'career_history';

    protected $fillable = [
        'user_id',
        'cabang_id',
        'structure_organization_custom_id',
        'effective_date',
        'job_desc',
        'status',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'start_date' => 'date',
        'end_date' =>  'date'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';
}
