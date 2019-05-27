<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlInformation extends Model
{
    protected $table = 'url_info';
    protected $fillable = array(
        'id',
        'project_code',
        'url',
        'method',
        'in_time',
        'out_time',
        'message',
        'event',
        'ip_address',
        'user_id',
        'duration',
        'created_at',
        'updated_at'
    );
}


