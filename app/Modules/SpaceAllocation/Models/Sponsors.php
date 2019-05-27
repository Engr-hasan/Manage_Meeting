<?php

namespace App\Modules\SpaceAllocation\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Sponsors extends Model {

    protected $table = 'space_sponsors';
    protected $fillable = array(
        'id',
        'reg_id',
        'sponsor_name',
        'sponsor_address',
        'sponsor_nationality',
        'sponsor_status',
        'sponsor_share_ext',
        'status',
        'is_archive',
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
