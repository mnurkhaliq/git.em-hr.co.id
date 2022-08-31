<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    public function author(){
        return $this->belongsTo('App\User','user_created');
    }
}
