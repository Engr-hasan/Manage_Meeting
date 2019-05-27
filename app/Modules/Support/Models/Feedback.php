<?php
namespace App\Modules\Support\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {
    protected $table = 'feedback';
    protected $fillable = array(
        'id',
        'topic_id',
        'description',
        'status',
        'priority',
        'ratings',
        'user_sub_type',
        'assigned_to',
        'assigned_by',
        'parent_id',
        'created_by',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }
}