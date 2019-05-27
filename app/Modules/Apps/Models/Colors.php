<?php

namespace App\Modules\apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class Colors extends Model {

    protected $table = 'colors';
    protected $fillable = array(
        'id',
        'name',
        'code',
        'is_active',
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
