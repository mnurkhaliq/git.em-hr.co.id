<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingActivityVisit extends Model
{
    protected $table = 'setting_visit_activity';
    public function CategoryActivityVisit()
    {
        return $this->belongsTo('\App\Models\MasterCategoryVisit', 'master_category_visit_id', 'id');
    }
}
