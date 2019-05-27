<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserTypes extends Model {

    protected $table = 'user_types';
    protected $fillable = array(
        'id',
        'type_name',
        'is_registarable',
        'access_code',
        'user_manual_name',
        'permission_json',
        'delegate_to_types',
        'status',
    );

    /************************ Users Model Class ends here ****************************/
}
