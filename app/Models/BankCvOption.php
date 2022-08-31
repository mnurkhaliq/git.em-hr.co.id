<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankCvOption extends Model
{
    protected $table = 'bank_cv_option';
    protected $guarded = [];

    public function values()
    {
    	return $this->hasMany('\App\Models\BankCvOptionValue', 'bank_cv_option_id', 'id');
    }
}
