<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersBranchVisit extends Model
{
    protected $table = 'users_branch_visit';

    protected $casts = ['cabang_id' => 'array'];

    protected $guarded = [];

    public function cabangPicMasters()
    {
        return $this->hasMany('\App\Models\CabangPicMaster', 'cabang_id', 'cabang_id')->where('cabangpicmaster.isactive', 1);
    }

    public function cabang()
    {
        return $this->belongsTo('\App\Models\Cabang', 'cabang_id');
    }
}
