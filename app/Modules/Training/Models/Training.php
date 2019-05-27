<?php

namespace App\Modules\Training\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Training extends Model {

    protected $table = 'trainings';
    protected $fillable = array(
        'id',
        'user_types',
        'public_user_types',
        'title',
        'description',
        'status',
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

}
