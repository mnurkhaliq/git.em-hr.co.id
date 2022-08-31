<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmBlogComment extends Model
{
    protected $connection = 'crm';

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'write_date';

    protected $table = 'emhr_comment';

    public function replies()
    {
        return $this->hasMany(CrmBlogComment::class, 'parent_id')->where('is_publish', true)->orderBy('create_date', 'DESC');
    }

    public function user()
    {
    	return $this->hasOne('\App\Models\CrmCommentUser', 'id', 'user_id');
    }

    public function getTimeAgo($carbonObject) {
        return str_ireplace(
            [' seconds', ' second', ' minutes', ' minute', ' hours', ' hour', ' days', ' day', ' weeks', ' week'], 
            ['s', 's', 'm', 'm', 'h', 'h', 'd', 'd', 'w', 'w'], 
            $carbonObject->diffForHumans()
        );
    }
}
