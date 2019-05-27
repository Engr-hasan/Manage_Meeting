<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class HsCodes extends Model {

    protected $table = 'hs_codes';
    protected $fillable = array(
        'id',
        'hs_code',
        'product_name',
        'is_active',
        'is_locked',
        'is_archive',
        'created_by',
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

    /*     * *******************************************End of Model Class********************************************* */
}
