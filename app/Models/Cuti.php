<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuti extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'cuti';
    protected $casts = [
        'iscarryforward' => 'boolean',
        'is_place' => 'boolean',
    ];
    
    public function cutiname()
    {
        return $this->belongsTo('\App\Models\MasterCutiType','master_cuti_type_id');
    }

    public function userCuti()
    {
        return $this->hasMany('\App\Models\UserCuti');
    }
}
