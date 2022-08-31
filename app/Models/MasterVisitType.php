<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterVisitType extends Model
{
    protected $table = 'master_visit_type';

    protected $guarded = [];

    public function userVisitType()
    {
    	return $this->belongsTo('\App\User', 'id', 'master_visit_type_id');
    }
}
