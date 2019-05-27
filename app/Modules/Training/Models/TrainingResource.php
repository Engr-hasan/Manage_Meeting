<?php

namespace App\Modules\Training\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TrainingResource extends Model {

    protected $table = 'training_resource';
    protected $fillable = array(
        'id',
        'training_id',
        'resource_title',
        'resource_type',
        'resource_link',
        'status',
        'is_deleted',
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
