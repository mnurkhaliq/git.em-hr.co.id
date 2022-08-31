<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmProduct extends Model
{
    protected $connection = 'crm';

    protected $table = 'product_template';
}
