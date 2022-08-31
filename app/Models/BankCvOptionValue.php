<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankCvOptionValue extends Model
{
    protected $table = 'bank_cv_option_value';
    protected $guarded = [];

    public function option()
    {
    	return $this->belongsTo('\App\Models\BankCvOption', 'bank_cv_option_id', 'id');
    }
}
