<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvanceForm extends Model
{
    use HasFactory;

    protected $table = 'cash_advance_form';

    public function bensin(){
        return $this->hasOne('App\Models\CashAdvanceBensin','cash_advance_form_id','id');
    }

    public function cashAdvance(){
        return $this->hasOne('App\Models\CashAdvance','id','cash_advance_id');
    }
}
