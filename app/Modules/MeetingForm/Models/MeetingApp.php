<?php

namespace App\Modules\MeetingForm\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MeetingApp extends Model {

    protected $table = 'meeting_app';
    protected $fillable = array(
        'id',
        'date_of_submission',
        'date_of_approval',
        'task_name',
        'comments',
        'task_description',
        'remarks',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
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


    /*     * *****************************End of Model Class********************************** */
}
