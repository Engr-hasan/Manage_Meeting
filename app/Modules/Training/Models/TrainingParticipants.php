<?php
/**
 * Created by PhpStorm.
 * User: Zaman
 * Date: 10/26/2016
 * Time: 1:58 PM
 */

namespace App\Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;


class TrainingParticipants extends Model
{
    protected $table = 'training_participants';

    protected $fillable = array(
        'id',
        'training_schedule_id',
        'user_id',
        'name',
        'email',
        'mobile',
        'district',
        'trainee_nid',
        'bank',
        'agency_name',
        'agency_license',
        'dob'
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