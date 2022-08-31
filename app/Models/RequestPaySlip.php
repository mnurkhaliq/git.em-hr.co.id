<?php

namespace App\Models;

use Barryvdh\DomPDF\PDF;
use Illuminate\Database\Eloquent\Model;

class RequestPaySlip extends Model
{
    protected $table = 'request_pay_slip';

    /**
     * [user description]
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function items()
    {
        return $this->hasMany('App\Models\RequestPaySlipItem', 'request_pay_slip_id', 'id');
    }

}
