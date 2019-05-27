<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class AgendaRemarks extends Model {

    protected $table = 'agenda_list_remarks';
    protected $fillable = array(
        'id',
        'agenda_id',
        'user_id',
        'chairman',
        'remarks',
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
