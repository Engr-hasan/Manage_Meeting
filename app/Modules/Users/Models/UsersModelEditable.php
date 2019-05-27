<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class UsersModelEditable extends Model {

    protected $table = 'users';
    protected $fillable = array(
        'user_type',
        'user_sub_type',
        'code',
        'password',
        'user_social_type',
        'user_social_id',
        'user_hash',
        'user_status',
        'user_verification',
        'user_full_name',
        'user_nid',
        'passport_no',
        'user_DOB',
        'user_gender',
        'user_street_address',
        'user_country',
        'user_city',
        'user_division',
        'user_pic',
        'signature',
        'house_no',
        'road_no',
        'post_code',
        'designation',
        'user_zip',
        'user_phone',
        'authorization_file',
        'user_first_login',
        'user_language',
        'security_profile_id',
        'district',
        'thana',
        'state',
        'province',
        'details',
        'user_agreement',
        'is_approved',
        'remember_token',
        'updated_by',
        'user_hash_expire_time',
        'auth_token',
        'auth_token_allow',
        'bank_branch_id'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = CommonFunction::getUserId();
                $post->updated_by = CommonFunction::getUserId();
            }
        });

        static::updating(function($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = Auth::user()->id;
            }
        });
    }


    /*     * ***************************** Users Model Class ends here ************************* */
}
