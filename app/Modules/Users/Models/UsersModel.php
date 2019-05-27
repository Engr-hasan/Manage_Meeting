<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Libraries\CommonFunction;

class UsersModel extends Model {
    protected $table = 'users';
    protected $fillable = array(
        'id',
        'user_type',
        'delegate_to_user_id',
        'user_email',
        'password'     
    );

    protected $defaults = array(
        'authorization_file' => '',
        'is_locked' => 0,
        'district' => 0,
        'thana' => 0,
        'details' => ''
    );
    public function __construct(array $attributes = array())
    {
        $this->setRawAttributes($this->defaults, true);
        parent::__construct($attributes);
    }

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
                $post->updated_by = CommonFunction::getUserId();
            }
        });
    }

    // This function will be used to check if a value is already existed in a database or not
    function check_availability_status($field, $value, $user_social_id, $type) {
        return DB::table($this->table)
                        ->where($field, $value)
                        ->where('user_social_type', 'google')
                        ->where('user_social_id', $user_social_id)
                        ->get();
    }

    function checkUserByEmail($user_email) {
        return DB::table($this->table)
                        ->where("user_email", $user_email)
                        ->get();
    }

    function chekced_verified($TOKEN_NO, $data) {
        DB::table($this->table)
                ->where('user_hash', $TOKEN_NO)
                ->update($data);
    }

    function profile_update($table, $field, $check, $value) {
        return DB::table($table)->where($field, $check)->update($value);
    }



    function getListWhere($field, $value) {
        return DB::table($this->table)
                        ->where($field, $value)
                        ->get();
    }

    function getTotalUserList() {
        return DB::table($this->table)->count();
    }

    //this function is to check if the email address is associated with user table
    //and if valid return type id
    function checkEmailAndGetType($user_email) {
        return DB::table($this->table)
                        ->where('user_email', $user_email)
                        ->pluck('user_type');
    }

    function checkEmailAndGetMemId($user_email) {
        return DB::table($this->table)
                        ->where('user_email', $user_email)
                        ->pluck('id');
    }

    public static function setLanguage($lang) {
        UsersModel::find(Auth::user()->id)->update(['user_language' => $lang]);
    }
    /*     * ***************************** Users Model Class ends here ************************* */
}
