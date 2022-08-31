<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisasiPosition extends Model
{
    protected $table = 'organisasi_position';

    public function loanPlafond()
    {
    	return $this->hasOne('App\Models\LoanPlafond', 'organisasi_position_id', 'id');
    }
}
