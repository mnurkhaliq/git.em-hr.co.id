<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CutiKaryawanDate extends Model
{
    protected $guarded = [];

    public function cutiKaryawan(){
        return $this->belongsTo('App\Models\CutiKaryawan');
    }
}
