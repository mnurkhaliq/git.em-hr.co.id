<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsImage extends Model
{
    protected $table = 'news_images';

    public function news(){
        return $this->belongsTo('App\Models\News', 'news_id');
    }
}
