<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActionInformation extends Model
{
    protected $table = 'action_info';
    protected $fillable = array(
        'id',
        'project_code',
        'url',
        'action',
        'ip_address',
        'user_id',
        'message',
        'created_at',
        'updated_at'
    );
}


