<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class PdfServerInfo extends Model {

    protected $table = 'pdf_service_info';
    protected $fillable = array(
        'id',
        'project_code',
        'module',
        'certificate_type',
        'server_type',
        'key',
        'pdf_server_url',
        'created_at',
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

    //********************************************End of Model Class**********************************************/
}
