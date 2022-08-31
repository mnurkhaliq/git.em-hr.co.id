<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BirthdayWording extends Model
{
    use HasFactory;
    protected $table = 'birthday_wording';
    protected $fillable = ['word'];
}
