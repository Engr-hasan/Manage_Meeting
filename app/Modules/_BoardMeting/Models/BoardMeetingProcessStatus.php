<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class BoardMeetingProcessStatus extends Model {

    protected $table = 'board_meeting_process_status';
    protected $fillable = array(
        'id',
        'status_name',
        'is_active',
        'type_id',
        'is_archive',
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
