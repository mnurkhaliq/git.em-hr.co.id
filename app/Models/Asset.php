<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'asset';

    /**
     * [department description]
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function pic()
    {
    	return $this->hasOne('App\User', 'id', 'pic_id');
    }

    public function userNoteBy()
    {
    	return $this->belongsTo('App\User', 'user_note_by', 'id');
    }

    /**
     * [department description]
     * @return [type] [description]
     */
    public function asset_type()
    {
    	return $this->hasOne('App\Models\AssetType', 'id', 'asset_type_id');
    }

    public function history(){
        return $this->hasMany('App\Models\AssetTracking', 'asset_id', 'id');
    }
}
