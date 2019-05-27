<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class Users extends Model {

    protected $table = 'users';
    protected $fillable = array(
        'id',
        'user_full_name',
        'user_sub_type',
        'user_DOB',
        'user_phone',
        'user_email',
        'designation',
        'user_hash',
        'social_login',
        'user_type',
        'country',
        'nationality',
        'desk_id',
        'identity_type',
        'passport_no',
        'user_nid',
        'division',
        'district',
        'state',
        'province',
        'road_no',
        'house_no',
        'user_pic',
        'post_code',
        'user_fax',
        'is_approved',
        'user_status',
        'user_agreement',
        'first_login',
        'user_verification',
        'delegate_to_user_id',
        'delegate_by_user_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
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

    function chekced_verified($TOKEN_NO, $data) {
        DB::table($this->table)
                ->where('user_hash', $TOKEN_NO)
                ->update($data);
    }

    function profile_update($table, $field, $check, $value) {
        return DB::table($table)->where($field, $check)->update($value);
    }

    function getUserList()
    {
        $users_type = Auth::user()->user_type;
        $type = explode('x', $users_type)[0];
        if ($type == 1)
        { // 1x101 for Super Admin
            return Users::leftJoin('user_types as mty', 'mty.id', '=', 'users.user_type')
//                            ->leftJoin('user_desk as ud', 'ud.desk_id', '=', 'users.desk_id')
                            ->leftJoin('area_info', 'users.district', '=', 'area_info.area_id')
                            ->leftJoin('area_info as ai', 'users.thana', '=', 'ai.area_id')
                            ->leftJoin('company_info as ci', 'users.user_sub_type', '=', 'ci.id') // will be applied only in case of applicant users
                            ->orderBy('users.id', 'desc')
                            ->orderBy('users.created_at', 'desc')
//                            ->where('users.user_agreement', '!=', 0)
                            ->where('users.user_status', '!=', 'rejected')
//                            ->where('users.user_type', '!=', Auth::user()->user_type)
                            ->get(['users.user_full_name','users.created_at', 'users.user_sub_type', 'users.user_email',
                                'users.user_status', 'users.login_token', 'users.user_first_login', 'users.id',
                                'ai.area_nm as thana', 'area_info.area_nm as users_district',
                                'users.user_type','mty.type_name','ci.company_name']);
        }
    }
    function getHistory($email)
    {
        $users_type = Auth::user()->user_type;
        $type = explode('x', $users_type)[0];
        if ($type == 1)
        { // 1x101 for Super Admin
            return DB::table('failed_login_history')->where('user_email',$email)->get(['user_email','remote_address','created_at']);
//                            ->where('users.user_type', '!=', Auth::user()->user_type
        }
    }


    function getUserRow($user_id) {
        return Users::leftJoin('user_types as mty', 'mty.id', '=', 'users.user_type')
                        ->leftJoin('park_info as pi', 'pi.id', '=', 'users.park_id')
                        ->where('users.id', $user_id)
                        ->first(['users.*', 'pi.id as ezid', 'pi.park_name as ez_name', 'mty.type_name','mty.id as type_id']);
    }

    function checkEmailAndGetMemId($user_email) {
        return DB::table($this->table)
                        ->where('user_email', $user_email)
                        ->pluck('id');
    }

    public static function setLanguage($lang) {
        Users::find(Auth::user()->id)->update(['user_language' => $lang]);
    }

    /**
     * @param $users object of logged in user
     * @return array
     */
    public static function getUserSpecialFields($users) {
        $additional_info = [];
        $user_type = explode('x', $users->user_type)[0];

        switch ($user_type) {

            case 4:  //SB
                $additional_info = [
                    [
                        'caption' => 'District',
                        'value' => $users->district != 0 ? AreaInfo::where('area_id', $users->district)->pluck('area_nm') : '',
                        'caption_thana' => 'Thana',
                        'value_thana' => $users->thana != 0 ? AreaInfo::where('area_id', $users->thana)->pluck('area_nm') : ''
                    ]
                ];
                break;
        }
        return $additional_info;
    }

    /*     * ***************************** Users Model Class ends here ************************* */
}
