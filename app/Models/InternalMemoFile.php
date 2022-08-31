<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalMemoFile extends Model
{
    protected $table = 'internal_memo_files';

    public function internalMemo(){
        return $this->belongsTo('App\Models\InternalMemo', 'internal_memo_id');
    }
}
