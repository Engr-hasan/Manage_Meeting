<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class SbArea extends Model {

    protected $table = 'area_info';
    protected $fillable = array(
      'area_id','area_nm','pare_id', 'area_type','area_nm_ban'
    );

    public $timestamps = false;

}