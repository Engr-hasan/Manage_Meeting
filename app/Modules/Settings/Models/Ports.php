<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Ports extends Model {

    protected $table = 'ports';
    protected $fillable = array(
        'id',
        'name',
        'country_iso',
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
