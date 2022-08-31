<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmPrivacy extends Model
{
    protected $connection = 'crm';

    protected $table = 'emhr_privacy';
}
