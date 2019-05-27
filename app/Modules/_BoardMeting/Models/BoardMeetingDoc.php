<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class BoardMeetingDoc extends Model {

    protected $table = 'board_meeting_doc';
    protected $fillable = array(
        'id',
        'doc_name',
        'file',
        'tag',
        'ctg_id',
        'board_meting_id',
        'agenda_id',
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
    public static function getList(){
        $boardMeeting = BoardMeetingDoc::where('is_active',1)
            ->where('ctg_id', 2)
            ->orderBy('id','DESC')
            ->get(['id','doc_name']);

        return $boardMeeting;
    }


    /************************ Countries Model Class ends here ****************************/
}
