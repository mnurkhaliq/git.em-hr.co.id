<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalMemo extends Model
{
    protected $table = 'internal_memo';

    public function author(){
        return $this->belongsTo('App\User', 'user_created');
    }

    public function files(){
        return $this->hasMany('App\Models\InternalMemoFile', 'internal_memo_id');
    }
}
