<?php

namespace App\Modules\ProcessPath\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class ProcessPath extends Model {

    protected $table = 'process_path';
    protected $fillable = array(
        'id',
        'process_type_id',
        'desk_from',
        'desk_to',
        'status_from',
        'status_to',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
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