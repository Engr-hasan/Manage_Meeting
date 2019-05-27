<?php

namespace App\Modules\MeetingForm\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConstructiveActivities extends Model {

    protected $table = 'previous_month_constructive_activities';
    protected $fillable = array(
        'id',
        'app_id',
        'is_old',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

    /**
     * @return application list
     */


    /*     * *****************************End of Model Class********************************** */
}
