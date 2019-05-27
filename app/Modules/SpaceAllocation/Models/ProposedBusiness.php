<?php

namespace App\Modules\SpaceAllocation\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ProposedBusiness extends Model {

    protected $table = 'space_proposed_business';
    protected $fillable = array(
        'id',
        'type_id',
        'reg_id',
        'description',
        'unit_id',
        'qty_1st',
        'qty_2nd',
        'qty_3rd',
        'qty_4th',
        'qty_5th',
        'qty_total',
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
