<?php

namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class P2DocInfo extends Model {

    protected $table = 'doc_info';
    protected $fillable = [
        'id',
        'process_type_id',
        'doc_name',
        'is_required',
        'additional_field',
        'order',
        'is_active',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

    /*     * *****************************End of Model Function********************************* */
}
