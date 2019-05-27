<?php

namespace App\Modules\CoBrandedCard\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CoBrandedBusinessPurpose extends Model {

    protected $table = 'co_branded_business_purpose';
    protected $fillable = array(
        'id',
        'name',
        'is_active',
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
