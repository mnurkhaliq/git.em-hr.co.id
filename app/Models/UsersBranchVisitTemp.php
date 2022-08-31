<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersBranchVisitTemp extends Model
{
    protected $table = 'users_branch_visit_temp';
    
    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo('\App\Models\Cabang', 'cabang_id', 'id');
    }
}
