<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Setting extends Model
{
    protected $table = 'setting';
    protected $guarded = [];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if(session('db_name',null) != null) {
            $this->setConnection(session('db_name'));
//            info('DB changed to : '.session('db_name'));
        }
//        info('Setting accessed');
    }
}
