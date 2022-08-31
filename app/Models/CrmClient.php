<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmClient extends Model
{
    //
    protected $connection = 'crm';
    // protected $table = 'crm_client';
    protected $table = 'res_partner';
}
