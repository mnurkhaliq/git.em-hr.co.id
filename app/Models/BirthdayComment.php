<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BirthdayComment extends Model
{
    use HasFactory;
    protected $table = 'birthday_comment';

    public function user()
    {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function commentBy()
    {
    	return $this->hasOne('App\User', 'id', 'comment_by');
    }

    public function birthdayCommentLike()
    {
        return $this->hasMany('App\Models\BirthdayCommentLike', 'comment_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\BirthdayComment', 'parent_id', 'id')->orderBy('id', 'DESC');
    }
}
