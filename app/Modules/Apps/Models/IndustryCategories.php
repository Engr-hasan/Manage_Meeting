<?php

namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class IndustryCategories extends Model {

    protected $table = 'industry_categories';
    protected $fillable = array(
        'id',
        'name',
        'color_id',
        'is_active',
        'is_locked',
        'is_archieved',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
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


    /*     * *****************************************End of Model Class**************************************************** */
}
