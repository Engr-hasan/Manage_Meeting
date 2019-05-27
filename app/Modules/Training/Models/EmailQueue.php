<?php

namespace App\Modules\Training\Models;
use Illuminate\Database\Eloquent\Model;

Class EmailQueue extends Model
{
    protected $table = 'email_queue';
    protected $fillable = array(
        'email_content',
        'email_to',
        'email_cc',
        'attachment',
        'secret_key',
        'status'
    );

    public static function boot()
    {
        parent::boot();
    }
}