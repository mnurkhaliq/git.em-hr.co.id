<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    public function author(){
        return $this->belongsTo('App\User', 'user_created');
    }

    public function images(){
        return $this->hasMany('App\Models\NewsImage', 'news_id');
    }

    public function first_image() {
        return $this->hasOne('App\Models\NewsImage');
    }
}
