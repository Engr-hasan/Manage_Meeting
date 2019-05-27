<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Configuration extends Model {

    protected $table = 'configuration';
    protected $fillable = array(
      'id','caption','value', 'details','value2','value3','created_by', 'updated_by','is_locked'
    );

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            $post->created_by = Auth::user()->id;
            $post->created_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

    public static function getLastDate($type='')
    {

        $config = DB::select(DB::raw("select CASE WHEN date1<getLastDateGovt THEN date1 ELSE getLastDateGovt END AS Government,CASE WHEN date1<getLastDatePriv THEN date1 ELSE getLastDatePriv END AS Private
                    from(
                    (SELECT value2 as getLastDateGovt FROM configuration WHERE caption='PRE_REG_GOVT_PERIOD') A
                    JOIN
                    (SELECT value2 as getLastDatePriv FROM configuration WHERE caption='PRE_REG_PRIVATE_PERIOD') B ON 1=1
                    JOIN
                    (SELECT DATE(DATE_ADD(NOW(),INTERVAL (SELECT VALUE as valid FROM configuration WHERE caption='DRAFT_PILGRIM_DATA_VALIDITY') DAY)) as date1 ) D ON 1=1
                    )"));

        if($type =='Private'){
            return $config[0]->Private;
        }elseif($type =='Government'){
            return $config[0]->Government;
        }else{
            return $config[0];
        }

    }

}