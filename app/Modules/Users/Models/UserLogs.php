<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserLogs extends Model {

    protected $table = 'user_logs';
    protected $fillable = array(
        'id',
        'user_id',
        'ip_address',
        'access_log_id',
        'login_dt',
        'logout_dt',
    );

    /************************ Users Model Class ends here ****************************/
}
