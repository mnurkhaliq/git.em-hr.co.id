<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvanceBensin extends Model
{
    use HasFactory;
    protected $table = 'cash_advance_bensin';
    public function form(){
        return $this->belongsTo('App\Models\CashAdvanceForm','cash_advance_form_id');
    }
}
