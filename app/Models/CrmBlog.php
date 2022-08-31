<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmBlog extends Model
{
    protected $connection = 'crm';

    protected $table = 'emhr_blog';

    public function comments()
    {
        return $this->morphMany(CrmBlogComment::class, 'commentable')->whereNull('parent_id');
    }
    
    public function category()
    {
    	return $this->hasOne('\App\Models\CrmProduct', 'id', 'category_id');
    }

}
