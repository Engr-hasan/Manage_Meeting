<?php

namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Libraries\CommonFunction;

class DocInfo extends Model {

    protected $table = 'doc_info';
    protected $primaryKey = 'doc_id';
    protected $fillable = [
        'doc_id',
        'process_type_id',
        'ctg_id',
        'doc_name',
        'doc_priority',
        'is_multiple',
        'order',
    ];

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

    /*     * *****************************End of Model Function********************************* */
}
