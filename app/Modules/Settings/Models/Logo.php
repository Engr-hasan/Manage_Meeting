<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model {

    protected $table = 'logo_information';
    protected $fillable = array(
        'id',
        'logo',
        'title',
        'manage_by',
        'help_link',
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
