<?php namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
class EmailQueue extends Model {

    protected $table = 'email_queue';
    protected $fillable = array(
        'id',
        'app_id',
        'user_id',
        'service_id',
        'email_content',
        'email_to',
        'email_cc',
        'attachment',
        'email_status',
        'sms_content',
        'sms_to',
        'sms_status',
        'secret_key',
        'pdf_type',
        'created_at',
        'updated_at',
    );


}
