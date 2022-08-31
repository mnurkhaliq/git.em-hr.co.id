<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobseekerOption extends Model
{
    protected $table = 'jobseekers_option';

    public function jobseekers()
    {
        return $this->belongsTo('App\Models\Jobseekers', 'jobseekers_id', 'id');
    }

    public function option()
    {
        return $this->belongsTo('App\Models\BankCvOption', 'bank_cv_option_id', 'id');
    }

    public function value()
    {
        return $this->belongsTo('App\Models\BankCvOptionValue', 'bank_cv_option_value_id', 'id');
    }
}
