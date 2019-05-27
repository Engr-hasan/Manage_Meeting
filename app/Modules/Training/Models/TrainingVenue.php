<?php

namespace App\Modules\Training\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TrainingVenue extends Model {

    protected $table = 'training_venue';
    protected $fillable = array(
        'id',
        'venue_name',
        'venue_id',
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
