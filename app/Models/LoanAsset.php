<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanAsset extends Model
{
    protected $table = 'loan_asset';

    public function loan()
    {
    	return $this->belongsTo('App\Models\Loan', 'loan_id', 'id');
    }
}
