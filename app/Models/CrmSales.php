<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmSales extends Model
{
    protected $connection = 'crm';

    protected $table = 'res_partner';
}
