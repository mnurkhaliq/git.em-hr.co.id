<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeSheetForm extends Model
{
    protected $table = 'overtime_sheet_form';
    protected $guarded = [];

    public function absensi_item()
    {
    	return $this->hasOne('App\Models\AbsensiItem', 'date', 'tanggal');
    }

    public function overtimeSheet()
    {
    	return $this->belongsTo('App\Models\OvertimeSheet');
    }
}
