<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabangPicMaster extends Model
{
    protected $table = 'cabangpicmaster';

    protected $guarded = [];

    public function cabang()
    {
        return $this->belongsTo('\App\Models\Cabang', 'cabang_id');
    }

    public function cabangPics()
    {
        return $this->hasMany('\App\Models\CabangPic', 'cabangpicmaster_id');
    }

    public function branchname()
    {
        return $this->belongsTo('\App\Models\Cabang','cabang_id','id');
    }
}
