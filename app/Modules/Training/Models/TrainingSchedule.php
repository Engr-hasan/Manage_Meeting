<?php

namespace App\Modules\Training\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TrainingSchedule extends Model {

    protected $table = 'training_schedule';
    protected $fillable = array(
        'id',
        'training_id',
        'trainer_name',
        'venue',
        'total_seats',
        'location',
        'start_time',
        'end_time',
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
