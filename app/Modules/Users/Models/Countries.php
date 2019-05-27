<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Countries extends Model {

    protected $table = 'country_info';
    protected $fillable = array(
        'id',
        'country_code',
        'iso',
        'name',
        'nicename',
        'nationality',
        'iso3',
        'numcode',
        'phonecode',
        'country_priority',
        'country_status',
    );

    /************************ Countries Model Class ends here ****************************/
}
