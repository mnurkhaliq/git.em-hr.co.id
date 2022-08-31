<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $table = 'import_log';

    protected $fillable = [
        'row_number',
        'message'
    ];
}
