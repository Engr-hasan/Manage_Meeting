<?php

namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class AppDocuments extends Model {

    protected $table = 'app_documents';
    protected $fillable = [
        'id',
        'process_type_id',
        'ref_id',
        'doc_info_id',
        'doc_name',
        'doc_file_path',
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
