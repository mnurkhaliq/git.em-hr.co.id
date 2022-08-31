<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BirthdayCommentLike extends Model
{
    use HasFactory;
    protected $table = 'birthday_comment_like';

    public function user()
    {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function likeBy()
    {
    	return $this->hasOne('App\User', 'id', 'like_by');
    }
}
