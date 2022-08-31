<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabangPic extends Model
{
    protected $table = 'cabangpic';

    protected $guarded = [];

    public function cabangPicMaster()
    {
        return $this->belongsTo('\App\Models\CabangPicMaster', 'cabangpicmaster_id')->where('cabangpicmaster.isactive', 1);
    }
}
