<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigDB extends Model
{
    public $timestamps = false;
    
    protected $connection = 'crm';
    // protected $table = 'crm_projects';
    protected $table = 'emhr_project';

    public function modules()
    {
        return $this->hasMany('\App\Models\ConfigDBModule', 'emhr_project_id', 'id');
    }
}
