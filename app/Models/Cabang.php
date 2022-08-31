<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabang';

    protected $guarded = [];

    public function cabangPicMasters()
    {
        return $this->hasMany('\App\Models\CabangPicMaster', 'cabang_id')->where('cabangpicmaster.isactive', 1);
    }

    public function usersBranchVisit()
    {
        return $this->hasMany('\App\Models\UsersBranchVisit', 'cabang_id');
    }
}
