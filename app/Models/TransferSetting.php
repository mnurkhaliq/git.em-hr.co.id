<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferSetting extends Model
{
    use HasFactory;
    
    protected $table = 'transfer_setting';

    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }
}
