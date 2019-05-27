<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model {

    protected $table = 'boared_meeting_committee';
    protected $fillable = array(
        'id',
        'board_meeting_id',
        'user_email',
        'user_name',
        'user_mobile',
        'designation',
        'organization',
        'user_phone',
        'type',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    );


    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

    /************************ Countries Model Class ends here ****************************/
}
