<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class AreaInfo extends Model {

    protected $table = 'area_info';
    protected $fillable = array(
        'area_id',
        'area_nm',
        'area_type',
        'pare_id',
        'dist_type',
        'area_nm_ban',
    );

    /************************ Users Model Class ends here ****************************/
}
