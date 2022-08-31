<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPict extends Model
{
    protected $table = 'visit_pict';

    protected $guarded = [];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function visitList()
    {
        return $this->belongsTo('\App\Models\VisitList', 'visit_list_id');
    }
    
    public function visitlistpict()
    {
        return $this->belongsTo('\App\Models\VisitList', 'visit_list_id', 'id');
    }
}
