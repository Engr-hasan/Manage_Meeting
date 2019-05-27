<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PdfPrintRequestQueue extends Model {

    protected $table = 'pdf_print_requests_queue';
    protected $fillable = array(
        'id',
        'process_type_id',
        'app_id',
        'others_significant_id',
        'url_requests',
        'pdf_server_url',
        'reg_key',
        'pdf_type',
        'certificate_name',
        'job_sending_status',
        'no_of_try_job_sending',
        'job_receiving_status',
        'no_of_try_job_receving',
        'certificate_link',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

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
/*********************************************End of Model Class**********************************************/
}
