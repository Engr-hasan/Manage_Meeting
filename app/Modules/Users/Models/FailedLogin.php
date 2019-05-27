<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class FailedLogin extends Model {

    protected $table = 'failed_login_history';
    protected $fillable = array(
        'remote_address',
        'user_email',
        'is_archive',
        'created_at'
    );

    /************************ Users Model Class ends here ****************************/
}
