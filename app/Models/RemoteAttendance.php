<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemoteAttendance extends Model
{
    protected $table = 'remote_attendance';

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
