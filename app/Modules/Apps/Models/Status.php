<?php namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;
class Status extends Model {

    protected $table = 'app_status';
    protected $fillable = array(
        'status_name',
        'process_id',
        'service_id',
        'color'
    );


}