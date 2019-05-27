<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model {

    protected $table = 'agenda';
    protected $fillable = array(
        'id',
        'name',
        'description',
        'file',
        'board_meting_id',
        'previous_board_meeting_id',
        'status',
        'remarks',
        'process_type_id',
        'is_active',
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
