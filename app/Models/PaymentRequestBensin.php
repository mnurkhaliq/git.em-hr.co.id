<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequestBensin extends Model
{
    protected $table = 'payment_request_bensin';
    public function form(){
        return $this->belongsTo('App\Models\PaymentRequestForm','payment_request_form_id');
    }
}
