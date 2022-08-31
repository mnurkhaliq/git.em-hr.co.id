<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigDBModule extends Model
{
    //
    protected $connection = 'crm';
    // protected $table = 'crm_projects';
    protected $table = 'emhr_module_emhr_project_rel';

    public function module()
    {
        return $this->belongsTo('\App\Models\Module', 'emhr_module_id', 'id');
    }
}
