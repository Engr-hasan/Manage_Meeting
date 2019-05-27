<?php

namespace App\Modules\SpaceAllocation\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class JointCompany extends Model {

    protected $table = 'space_joint_company';
    protected $fillable = array(
        'id',
        'reg_id',
        'joint_company',
        'joint_company_address',
        'joint_com_country',
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
