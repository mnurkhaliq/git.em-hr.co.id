<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPlafond extends Model
{
    protected $table = 'loan_plafond';

    public function position()
    {
    	return $this->belongsTo('\App\Models\OrganisasiPosition', 'organisasi_position_id', 'id');
    }
}
