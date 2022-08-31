<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequestForm extends Model
{
    protected $table = 'payment_request_form';

    public function bensin(){
        return $this->hasOne('App\Models\PaymentRequestBensin','payment_request_form_id','id');
    }

    public function paymentRequest(){
        return $this->hasOne('App\Models\PaymentRequest','id','payment_request_id');
    }
}
