<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequestType extends Model
{
    use HasFactory;
    protected $table = 'payment_request_type';
    protected $fillable = ['type', 'plafond', 'period', 'description'];
}
