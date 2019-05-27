<?php namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
class SmsQueue extends Model {

    protected $table = 'sms_queue';
    protected $fillable = array(
        'id',
        'app_id',
        'service_id',
        'sms_content',
        'sms_to',
        'status',
        'created_at',
        'updated_at',
    );


}
