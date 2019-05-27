<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Currencies extends Model {

    protected $table = 'currencies';
    protected $fillable = array(
        'id',
        'code',
        'name',
        'usd_value',
        'bdt_value',
        'is_active',
        'is_locked',
        'is_archive',
        'created_by',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        // Before update
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
