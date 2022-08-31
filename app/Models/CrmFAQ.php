<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmFAQ extends Model
{
    protected $connection = 'crm';

    protected $table = 'emhr_faq';
}
