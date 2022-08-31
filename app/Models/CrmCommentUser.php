<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmCommentUser extends Model
{
    protected $connection = 'crm';

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'write_date';

    protected $table = 'emhr_comment_user';


}
