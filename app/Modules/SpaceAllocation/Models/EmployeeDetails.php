<?php

namespace App\Modules\SpaceAllocation\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetails extends Model {

    protected $table = 'space_emp_details';
    protected $fillable = array(
        'id',
        'type_id',
        'reg_id',
        'year',
        'it_managerial',
        'it_skilled',
        'it_unskilled',
        'it_total',
        'ss_managerial',
        'ss_skilled',
        'ss_unskilled',
        'ss_total',
        'grand_total',
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
