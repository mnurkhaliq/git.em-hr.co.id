<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitList extends Model
{
    protected $table = 'visit_list';

    protected $guarded = [];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    public function cabang()
    {
        return $this->belongsTo('\App\Models\Cabang', 'cabang_id');
    }

    public function type()
    {
        return $this->belongsTo('\App\Models\MasterVisitType', 'master_visit_type_id');
    }

    public function category()
    {
        return $this->belongsTo('\App\Models\MasterCategoryVisit', 'master_category_visit_id');
    }

    public function visitPicts()
    {
        return $this->hasMany('\App\Models\VisitPict', 'visit_list_id');
    }

    public function VisitPhotos()
    {
        return $this->hasMany('\App\Models\VisitPict','id', 'visit_list_id');
    }
    
    public function cabangDetail()
    {
        return $this->hasOne('App\Models\Cabang', 'id', 'cabang_id');
    }
}
