<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class HighComissions extends Model {

    protected $table = 'high_comissions';
    protected $fillable = array(
        'id',
        'country_id',
        'name',
        'address',
        'phone',
        'email',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    );

        public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_at = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
    
/*******************************End of Model Class***********************************/
}
