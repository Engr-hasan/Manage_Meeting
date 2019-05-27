<?php

namespace App\Libraries;

use App\ActionInformation;
use App\AuditLog;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\Apps\Models\IndustryCategories;
use App\Modules\Apps\Models\ProcessList;
use App\Modules\Apps\Models\processVerifylist;
use App\Modules\BoardMeting\Models\Agenda;
use App\Modules\BoardMeting\Models\BoardMeting;
use App\Modules\BoardMeting\Models\Committee;
use App\Modules\BoardMeting\Models\ProcessListBMRemarks;
use App\Modules\BoardMeting\Models\ProcessListBoardMeting;
use App\Modules\Dashboard\Models\Status;
use App\Modules\Files\Controllers\FilesController;
use App\Modules\MeetingForm\Models\ConstructiveActivities;
use App\Modules\MeetingForm\Models\HumanResource;
use App\Modules\MeetingForm\Models\IncreasingEfficiency;
use App\Modules\MeetingForm\Models\NewMemberInclude;
use App\Modules\MeetingForm\Models\NextMonthPlan;
use App\Modules\MeetingForm\Models\NotableInformation;
use App\Modules\ProcessPath\Models\Desk;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\UserDesk;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\Logo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\ParkInfo;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UserTypes;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class CommonFunction
{

    /**
     * @param Carbon|string $updated_at
     * @param string $updated_by
     * @return string
     * @internal param $Users->id /string $updated_by
     */
    public static function showAuditLog($updated_at = '', $updated_by = '')
    {
        $update_was = 'Unknown';
        if ($updated_at && $updated_at > '0') {
            $update_was = Carbon::createFromFormat('Y-m-d H:i:s', $updated_at)->diffForHumans();
        }

        $user_name = 'Unknown';
        if ($updated_by) {
            $name = User::where('id', $updated_by)->first();
            if ($name) {
                $user_name = $name->user_full_name;
            }
        }
        return '<span class="help-block">Last updated : <i>' . $update_was . '</i> by <b>' . $user_name . '</b></span>';
    }

    public static function showErrorPublic($param, $msg = 'Sorry! Something went wrong! ')
    {
        $j = strpos($param, '(SQL:');
        if ($j > 15) {
            $param = substr($param, 8, $j - 9);
        } else {
            //
        }
        return $msg . $param;
    }



    public static function statuswiseAppInDesks($process_type_id)
    {
        $user = explode('x', Auth::user()->user_type);
        $company_id = CommonFunction::getUserSubTypeWithZero();
        $appsInDesk = ProcessStatus::leftJoin('process_list', function ($join) use ($process_type_id) {
            $join->on('process_status.id', '=', 'process_list.status_id');
            $join->on('process_list.process_type_id', '=', DB::raw($process_type_id));
        });
        $appsInDesk->where('company_id', $company_id);
//        if (in_array($user[0], [4, 6, 7])) { // desk users
//            $appsInDesk = $appsInDesk->where('process_list.desk_id', '=', DB::raw(Auth::user()->desk_id));
//        }
        $appsInDesk = $appsInDesk->where('process_status.process_type_id', $process_type_id)
            ->where('process_status.id', '!=', '-1')
            ->orderBy('process_status.id')
            ->groupBy('process_status.id')
            ->get(['status_name', 'process_status.process_type_id', 'process_status.id', DB::raw('count(process_list.ref_id) AS totalApplication')]);
//dd($appsInDesk);
        return $appsInDesk;
    }

    public static function showExamAuditLog($updated_at = '', $updated_by = '')
    {
        try {
            $update_was = 'Unknown';
            if ($updated_at && $updated_at > '0') {
                $update_was = Carbon::createFromFormat('Y-m-d H:i:s', $updated_at)->diffForHumans();
            }

            $user_name = 'Unknown';
            if ($updated_by) {
                $name = User::where('id', $updated_by)->first();
                if ($name) {
                    $user_name = $name->user_full_name;
                }
            }
            return '<span class="help-block">' . $update_was . ' </span>';
        } catch (\Exception $e) {
            if (env('APP_DEBUG')) {
                dd($e);
            } else {
                return 'Some errors occurred (code:790)';
            }
        }
    }

    public static function showCreateLog($created_at = '', $created_by = '', $msg = 'Created')
    {
        try {
            $update_was = 'Unknown';
            if ($created_at && $created_at > '0') {
                $update_was = Carbon::createFromFormat('Y-m-d H:i:s', $created_at)->diffForHumans();
            }

            $user_name = 'Unknown';
            if ($created_by) {
                $name = User::where('id', $created_by)->first();
                if ($name) {
                    $user_name = $name->user_full_name;
                }
            }
            return '<span class="help-block"> ' . $msg . ' at : <i>' . $update_was . '</i> by <b>' . $user_name . '</b></span>';
        } catch (\Exception $e) {
            if (env('APP_DEBUG')) {
                dd($e);
            } else {
                return 'Some errors occurred (code:790)';
            }
        }
    }

    public static function trainingAdmin()
    {
        return ['1x101', '2x202', '2x203', '4x401'];
    }

    public static function createAuditLog($module, $request, $id = '')
    {
        $data = $request->all();
        if ($id) {
            $data['id'] = $id;
        }
        try {
            unset($data['_token']);
            unset($data['_method']);
            unset($data['selected_file']);
            unset($data['TOKEN_NO']);
        } catch (\Exception $e) {
            echo 'Something wrong for audit log';
        }
        $details = json_encode($data);
        try {
            $sessionID = AuditLog::create([
                'remote_ip' => $request->ip(),
                'module' => $module,
                'details' => $details
            ]);
        } catch (\Exception $e) {
            echo 'Something wrong for audit log';
        }
    }

    public static function updatedOn($updated_at = '')
    {
        $update_was = '';
        if ($updated_at && $updated_at > '0') {
            $update_was = Carbon::createFromFormat('Y-m-d H:i:s', $updated_at)->diffForHumans();
        }
        return $update_was;
    }

    public static function updatedBy($updated_by = '')
    {
        $user_name = 'Unknown';
        if ($updated_by) {
            $name = User::find($updated_by);
            if ($name) {
                $user_name = $name->user_full_name;
            }
        }
        return $user_name;
    }

    public static function getUserId()
    {

        if (Auth::user()) {
            return Auth::user()->id;
        } else {
            return 'Invalid Login Id';
        }
    }

    public static function getUserType()
    {

        if (Auth::user()) {
            return Auth::user()->user_type;
        } else {
            // return 1;
            dd('Invalid User Type');
        }
    }

    public static function GlobalSettings()
    {
        $logoInfo = Logo::orderBy('id', 'DESC')->first();
        if ($logoInfo != "") {
            Session::set('logo', $logoInfo->logo);
            Session::set('title', $logoInfo->title);
            Session::set('manage_by', $logoInfo->manage_by);
            Session::set('help_link', $logoInfo->help_link);
        } else {
            Session::set('logo', 'assets/images/company_logo.png');
        }
        //return $logoInfo;
    }

    public static function getUserTypeWithZero()
    {

        if (Auth::user()) {
            return Auth::user()->user_type;
        } else {
            return 0;
        }
    }

    public static function getUserSubTypeWithZero()
    {

        if (Auth::user()) {
            return Auth::user()->user_sub_type;
        } else {
            return 0;
        }
    }

    public static function getDeskId()
    {
        if (Auth::user()) {
            return Auth::user()->desk_id;
        } else {
            CommonFunction::redirectToLogin();
        }
    }

    public static function redirectToLogin()
    {
        echo "<script>location.replace('users/login');</script>";
    }

    public static function formateDate($date = '')
    {
        return date('d.m.Y', strtotime($date));
    }

    public static function getUserStatus()
    {

        if (Auth::user()) {
            return Auth::user()->user_status;
        } else {
            // return 1;
            dd('Invalid User status');
        }
    }

    public static function getMonthCurrentPrevious($month){
        $month = $month;
        $formattedMonth = "";
        switch($month){
            case "01":
                $fomattedMonth = "ডিসেম্বর - জানুয়ারী";
                break;
            case "02":
                $fomattedMonth = "জানুয়ারী -ফেব্রুয়ারী ";
                break;
            case "03":
                $fomattedMonth = "ফেব্রুয়ারী-মার্চ  - মে";
                break;
            case "04":
                $fomattedMonth = "মার্চ - এপ্রিল";
                break;
            case "05":
                $fomattedMonth = "এপ্রিল - মে";
                break;
            case "06":
                $fomattedMonth = "মে - জুন";
                break;
            case "07":
                $fomattedMonth = "জুন  - জুলাই ";
                break;
            case "08":
                $fomattedMonth = "জুলাই - অগাস্ট ";
                break;
            case "09":
                    $fomattedMonth = "আগস্ট - সেপ্টেম্বর ";
                break;
            case "10":
                $fomattedMonth = "সেপ্টেম্বর - অক্টোবর ";
                break;
            case "11":
                $fomattedMonth = "অক্টোবর - নভেম্বর";
                break;
            case "12":
                $fomattedMonth = "নভেম্বর - ডিসেম্বর";
                break;
            default:
                $fomattedMonth = "";
        }
        return $fomattedMonth;
    }




    public static function convertUTF8($string)
    {
//        $string = 'u0986u09a8u09c7u09beu09dfu09beu09b0 u09b9u09c7u09beu09b8u09beu0987u09a8';
        $string = preg_replace('/u([0-9a-fA-F]+)/', '&#x$1;', $string);
        return html_entity_decode($string, ENT_COMPAT, 'UTF-8');
    }

    public static function showDate($updated_at = '')
    {
        if ($updated_at && $updated_at > '0') {
            $update_was = Carbon::createFromFormat('Y-m-d H:i:s', $updated_at)->diffForHumans();
        }

        return '<span class="help-block"><i>' . $update_was . '</i></span>';
    }

    public static function checkUpdate($model, $id, $updated_at)
    {
        if ($model::where('updated_at', $updated_at)->find($id)) {
            return true;
        } else {
            return false;
        }
    }

    /* This function determines if an user is an admin or sub-admin
     * Based On User Type
     *  */

    public static function isAdmin()
    {
        $user_type = Auth::user()->user_type;
        /*
         * 1x101 for System Admin
         * 5x501 for Agency Admin
         */
        if ($user_type == '1x101') {
            return true;
        } else {
            return false;
        }
    }

    public static function isBank()
    {
        $user_type = Auth::user()->user_type;
        if ($user_type == '11x421' || $user_type == '11x422') {
            return true;
        } else {
            return false;
        }
    }

    public static function changeDateFormat($datePicker, $mysql = false, $with_time = false)
    {
        try {
            if ($mysql) {
                if ($with_time) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $datePicker)->format('d-M-Y');
                } else {
                    return Carbon::createFromFormat('d-M-Y', $datePicker)->format('Y-m-d');
                }
            } else {
                return Carbon::createFromFormat('Y-m-d', $datePicker)->format('d-M-Y');
            }
        } catch (\Exception $e) {
            if (env('APP_DEBUG')) {
                dd($e);
            } else {
                return $datePicker; //'Some errors occurred (code:793)';
            }
        }
    }

    public static function validateMobileNumber($mobile_no)
    {
        $mobile_validation_err = '';
        $first_digit = substr($mobile_no, 0, 1);
        $first_two_digit = substr($mobile_no, 0, 2);
        $first_four_digit = substr($mobile_no, 0, 5);
        // if first two digit is 01
        if (strlen($mobile_no) < 11) {
            $mobile_validation_err = 'Mobile number should be minimum 11 digit';
        } elseif ($first_two_digit == '01') {
            if (strlen($mobile_no) != 11) {
                $mobile_validation_err = 'Mobile number should be 11 digit';
            }
        } // if first two digit is +880
        else if ($first_four_digit == '+8801') {
            if (strlen($mobile_no) != 14) {
                $mobile_validation_err = 'Mobile number should be 14 digit';
            }
        } // if first digit is only
        else if ($first_digit == '+') {
            // Mobile number will be ok
        } else {
            $mobile_validation_err = 'Please enter valid Mobile number';
        }

        if (strlen($mobile_validation_err) > 0) {
            return $mobile_validation_err;
        } else {
            return 'ok';
        }
    }

    public static function age($birthDate)
    {
        $year = '';
        if ($birthDate) {
            $year = Carbon::createFromFormat('Y-m-d', $birthDate)->diff(Carbon::now())->format('%y years, %m months and %d days');
        }
        return $year;
    }

    public static function getFieldName($id, $field, $search, $table)
    {

        if ($id == NULL || $id == '') {
            return '';
        } else {
            return DB::table($table)->where($field, $id)->pluck($search);
        }
    }


    public static function getUserDeskIds()
    {

        if (Auth::user()) {
            $deskIds = Auth::user()->desk_id;
            $userDeskIds = explode(',', $deskIds);
            return $userDeskIds;
        } else {
            // return 1;
            dd('Invalid User status');
        }
    }

    public static function getUserParkIds()
    {

        if (Auth::user()) {
            $parkIds = Auth::user()->park_id;
            $userParkIds = explode(',', $parkIds);
            return $userParkIds;
        } else {
            // return 1;
            dd('Invalid User status');
        }
    }

    public static function getUserDeskList()
    {
        if (Auth::user()) {
            $deskIds = Auth::user()->desk_id;
            $userDeskIds = explode(',', $deskIds);
            $userDeskList = UserDesk::whereIn('id', $userDeskIds)
                ->lists('user_desk.desk_name', 'user_desk.id')
                ->all();
            if (count($userDeskList) > 0)
                return $userDeskList;
            else return [0 => 'None'];
        } else {
            return 0;
        }
    }


    public static function getDelegatedUserDeskParkIds()
    {

        $userId = CommonFunction::getUserId();
        $delegated_usersArr = Users::where('delegate_to_user_id', $userId)
            ->get([
                'id as user_id',
                'desk_id',
                'park_id'
            ]);
        $delegatedDeskParkIds = array();
        foreach ($delegated_usersArr as $value) {

            $userDesk = explode(',', $value->desk_id);
            $userPark = explode(',', $value->park_id);
            $tempArr = array();
            $tempArr['user_id'] = $value->user_id;
            $tempArr['desk_ids'] = $userDesk;
            $tempArr['park_ids'] = $userPark;
            $delegatedDeskParkIds[$value->user_id] = $tempArr;
        }
        return $delegatedDeskParkIds;
    }


    public static function getSelfAndDelegatedUserDeskParkIds()
    {

        $userId = CommonFunction::getUserId();
        $delegated_usersArr = Users::where('delegate_to_user_id', $userId)
            ->orWhere('id', $userId)
            ->get([
                'id as user_id',
                'desk_id',
                'park_id'
            ]);
        $delegatedDeskParkIds = array();
        foreach ($delegated_usersArr as $value) {

            $userDesk = explode(',', $value->desk_id);
            $userPark = explode(',', $value->park_id);
            $tempArr = array();
            $tempArr['user_id'] = $value->user_id;
            $tempArr['desk_ids'] = $userDesk;
            $tempArr['park_ids'] = $userPark;
            $delegatedDeskParkIds[$value->user_id] = $tempArr;
        }
//        dd($delegatedDeskParkIds);
        return $delegatedDeskParkIds;
    }

    public static function hasDeskParkWisePermission($desk_id, $park_id)
    {

        $getSelfAndDelegatedUserDeskParkIds = CommonFunction::getSelfAndDelegatedUserDeskParkIds();
        foreach ($getSelfAndDelegatedUserDeskParkIds as $selfDeskId => $value) {
            if (in_array($desk_id, $value['desk_ids']) && in_array($park_id, $value['park_ids'])) {
                return true;
            }
        }
        return false;
    }


    public static function getDelegatedDeskIds()
    {
        $userId = CommonFunction::getUserId();
        $delegated_usersArr = Users::where('delegate_to_user_id', $userId)
            ->get([
                'id as user_id',
                'desk_id'
            ]);

        $delegatedDeskIds = array();
        foreach ($delegated_usersArr as $value) {
            $delegatedDeskIds[] = $value->desk_id;
        }

        return $delegatedDeskIds;
    }

    public static function getDelegatedParkIds()
    {
        $userId = CommonFunction::getUserId();
        $delegated_usersArr = Users::where('delegate_to_user_id', $userId)
            ->get([
                'id as user_id',
                'park_id'
            ]);

        $delegatedParkIds = array();
        foreach ($delegated_usersArr as $value) {
            $delegatedParkIds[] = $value->park_id;
        }

        return;
    }







//    public static function getDelegatedDeskIdsKeys() {
//        $userId = CommonFunction::getUserId();
//        $delegated_usersArr = Users::where('delegate_to_user_id', $userId)
//            ->get([
//                'id as user_id',
//                'desk_id'
//            ]);
//        $delegated_desks = "";
//        foreach ($delegated_usersArr as $value) {
//            if( !next( $delegated_usersArr ) )
//                $delegated_desks .= $value->desk_id.",";
//            else
//                $delegated_desks .= $value->desk_id;
//        }
//        return $delegated_desks;
//    }


    public static function DelegateUserInfo($desk_id)
    {

        $userID = CommonFunction::getUserId();
        $delegateUserInfo = Users::where('desk_id', 'like', '%' . $desk_id . '%')
            ->where('delegate_to_user_id', $userID)
            ->first([
                'id',
                'user_full_name',
                'user_email',
                'user_pic',
                'designation'
            ]);
        return $delegateUserInfo;
    }

    public static function getPicture($type, $ref_id)
    {
        $files = new FilesController();
        $img_data = $files->getFile(['type' => $type, 'ref_id' => $ref_id]);
        $json_data = json_decode($img_data->getContent());
        if ($json_data->responseCode == 1) {
            $base64 = $json_data->data;
        } else {
            $user_pic = User::where('id', $ref_id)->first(['user_pic']);
            $pos = strpos($user_pic, 'http');
            if ($pos === false) {
                $path = 'assets/images/no_image.png';
            } else {
                $path = $user_pic->user_pic;
            }
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return $base64;
    }

    public  static function getAcitvites($id){
        $data=ConstructiveActivities::where('app_id',$id)->orderBy('id','asc')->get();
        return $data;
    }
    public  static function getNotableInfo($id){
        $data=NotableInformation::where('app_id',$id)->orderBy('id','asc')->get();
        return $data;
    }

    public  static function getResource($id){
        $data=HumanResource::where('app_id',$id)->orderBy('id','asc')->get();
        return $data;
    }
    public  static function getEfficiency($id){
        $data=IncreasingEfficiency::where('app_id',$id)->orderBy('id','asc')->get();
        return $data;
    }
    public  static function getNewMember($id){
        $data=NewMemberInclude::where('app_id',$id)->orderBy('id','asc')->get();
        return $data;
    }
    public  static function getNextMOnthPlan($id){
        $data=NextMonthPlan::where('app_id',$id)->orderBy('id','asc')->get();
        return $data;
    }
    public static function getPreviousInitiative($company_id,$create_date){

        $date =date_parse_from_format("Y-m-d",$create_date);
        $month = $date['month'];
        $monthprevious=$month-1;
        $year=date('Y');
        if($year==12){
            $year=$year-1;
        }


        if($monthprevious<10){
            $date=$year.'-0'.$monthprevious.'-';
        }else{
            $date=$year.'-'.$monthprevious.'-';
        }



//dd($date);
        $data=ProcessList::leftjoin('planned_enterprises_next_month as p1','p1.app_id','=','process_list.ref_id')
           ->where('company_id',$company_id)
           ->where('process_list.created_at','like',$date.'%')
            ->get();
        return $data;
    }

    public static function getNotableWork($id){
        $data=ConstructiveActivities::where('app_id',$id)
            ->where('is_old',1)
            ->orderBy('id','asc')->get()->toArray();

        $data1=NotableInformation::where('app_id',$id)
            ->where('is_old',1)
            ->orderBy('id','asc')->get()->toArray();
        $data2=NewMemberInclude::where('app_id',$id)
            ->where('is_old',1)
            ->orderBy('id','asc')->get()->toArray();
        $data3=HumanResource::where('app_id',$id)
            ->where('is_old',1)
            ->orderBy('id','asc')->get()->toArray();
        $data4=IncreasingEfficiency::where('app_id',$id)
            ->where('is_old',1)
            ->orderBy('id','asc')->get()->toArray();
      return array_merge($data,$data1,$data2,$data3,$data4);







    }

    public static function convert2Bangla($eng_number)
    {
        $eng = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $ban = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($eng, $ban, $eng_number);
    }

    public static function convert2English($ban_number)
    {
        $eng = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $ban = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($ban, $eng, $ban_number);
    }

    public static function generateTrackingID($prefix, $id)
    {
        $prefix = strtoupper($prefix);
        $str = $id . date('Y') . mt_rand(0, 9);
        if ($prefix == 'M' || $prefix == 'N') {
            if (strlen($str) > 12) {
                $str = substr($str, strlen($str) - 12);
            }
        } elseif ($prefix == 'G') {
            if (strlen($str) > 10) {
                $str = substr($str, strlen($str) - 10);
            }
        } elseif ($prefix == 'T') {
            if (strlen($str) > 12) {
                $str = substr($str, strlen($str) - 12);
            }
        } else {
            if (strlen($str) > 14) {
                $str = substr($str, strlen($str) - 14);
            }
        }
        return $prefix . dechex($str);
    }


    public static function getImageConfig($type)
    {
        extract(CommonFunction::getImageDocConfig());
        $config = Configuration::where('caption', $type)->pluck('details');
        $reportHelper = new ReportHelper();
//        [File Format: *.jpg / *.png Dimension: {$height}x{$width}px File size($filesize)KB]
        if ($type == 'IMAGE_SIZE') {
            $data['width'] = ($IMAGE_WIDTH - ($IMAGE_WIDTH * $IMAGE_DIMENSION_PERCENT) / 100) . '-' . ($IMAGE_WIDTH + ($IMAGE_WIDTH * $IMAGE_DIMENSION_PERCENT) / 100);
            $data['height'] = ($IMAGE_HEIGHT - ($IMAGE_HEIGHT * $IMAGE_DIMENSION_PERCENT) / 100) . '-' . ($IMAGE_HEIGHT + ($IMAGE_HEIGHT * $IMAGE_DIMENSION_PERCENT) / 100);
            $data['variation'] = $IMAGE_DIMENSION_PERCENT;
            $data['filesize'] = $IMAGE_SIZE;
        } elseif ($type == 'DOC_IMAGE_SIZE') {
            $data['width'] = ($DOC_WIDTH - ($DOC_WIDTH * $IMAGE_DIMENSION_PERCENT) / 100) . '-' . ($DOC_WIDTH + ($DOC_WIDTH * $IMAGE_DIMENSION_PERCENT) / 100);
            $data['height'] = ($DOC_HEIGHT - ($DOC_HEIGHT * $IMAGE_DIMENSION_PERCENT) / 100) . '-' . ($DOC_HEIGHT + ($DOC_HEIGHT * $IMAGE_DIMENSION_PERCENT) / 100);
            $data['variation'] = $DOC_DIMENSION_PERCENT;
            $data['filesize'] = $DOC_SIZE;
        }
        $string = $reportHelper->ConvParaEx($config, $data);
        return $string;
    }

    //   ConvParaEx function imported from Report Helper Libraries
    public static function ConvParaEx($sql, $data, $sm = '{$', $em = '}', $optional = false)
    {
        $sql = ' ' . $sql;
        $start = strpos($sql, $sm);
        $i = 0;
        while ($start > 0) {
            if ($i++ > 20) {
                return $sql;
            }
            $end = strpos($sql, $em, $start);
            if ($end > $start) {
                $filed = substr($sql, $start + 2, $end - $start - 2);
                if (strtolower(substr($filed, 0, 8)) == 'optional') {
                    $optionalCond = self::ConvParaEx(substr($filed, 9), $data, '[$', ']', true);
                    $sql = substr($sql, 0, $start) . $optionalCond . substr($sql, $end + 1);
                } else {
                    $inputData = self::getData($filed, $data, substr($sql, 0, $start));
                    if ($optional && (($inputData == '') || ($inputData == "''"))) {
                        $sql = '';
                        break;
                    } else {
                        $sql = substr($sql, 0, $start) . $inputData . substr($sql, $end + 1);
                    }
                }
            }
            $start = strpos($sql, $sm);
        }
        return trim($sql);
    }

    public static function getData($filed, $data, $prefix = null)
    {
        dd($data);
        $filedKey = explode('|', $filed);
        $val = trim($data[$filedKey[0]]);
        if (!is_numeric($val)) {
            if ($prefix) {
                $prefix = strtoupper(trim($prefix));
                if (substr($prefix, strlen($prefix) - 3) == 'IN(') {
                    $vals = explode(',', $val);
                    $val = '';
                    for ($i = 0; $i < count($vals); $i++) {
                        if (is_numeric($vals[$i])) {
                            $val .= (strlen($val) > 0 ? ',' : '') . $vals[$i];
                        } else {
                            $val .= (strlen($val) > 0 ? ',' : '') . "'" . $vals[$i] . "'";
                        }
                    }
                } elseif (!(substr($prefix, strlen($prefix) - 1) == "'" || substr($prefix, strlen($prefix) - 1) == "%")) {
                    $val = "'" . $val . "'";
                }
            }
        }
        if ($val == '') $val = "''";
        return $val;
    }

    public static function deletePilgrim($pilgrim = false)
    {
        $ifLeader = Group::where(array('leader_id' => $pilgrim))->first();
        if (empty($ifLeader)) {
            $pilgrimRecord = Pilgrim::where(array('id' => $pilgrim, 'created_by' => Auth::user()->id, 'payment_status' => ' <10', 'group_payment_id' => '0'))->first();
            if ($pilgrimRecord) {

                if (ACL::getAccsessRight('pilgrim', 'D'))
                    return ' <a href="' . url('pilgrim/remove-pilgrim/' . Encryption::encodeId($pilgrim)) . '" onclick="return confirm(\'Are you sure to archive this pilgrim ?\');" class="btn btn-bg btn-danger pull-right" style="margin:0px 5px;"><i class="fa fa-trash-o"></i> Delete</a>';
                else
                    return '';
            } else {
                return false;
            }
        }
    }

    public static function getNotice($flag = 0)
    {
        if ($flag == 1) {
            $list = DB::select(DB::raw("SELECT date_format(updated_at,'%d %M, %Y') `Date`,heading,details,importance,id, case when importance='Top' then 1 else 0 end Priority FROM notice where status='public' or status='private' and is_active=1 and prefix=NULL order by Priority desc, updated_at desc LIMIT 10"));
        } else {
            $list = DB::select(DB::raw("SELECT date_format(updated_at,'%d %M, %Y') `Date`,heading,details,importance,id, case when importance='Top' then 1 else 0 end Priority FROM notice where status='public' and is_active=1 order by Priority desc, updated_at desc LIMIT 10"));
        }
        return $list;
    }

    public static function getImageDocConfig()
    {
        $config = array();
        $config['IMAGE_DIMENSION'] = Configuration::where('caption', 'IMAGE_SIZE')->pluck('value');
        $config['IMAGE_SIZE'] = Configuration::where('caption', 'IMAGE_SIZE')->pluck('value2');

        // Image size
        $split_img_size = explode('-', $config['IMAGE_SIZE']);
        $config['IMAGE_MIN_SIZE'] = $split_img_size[0];
        $config['IMAGE_MAX_SIZE'] = $split_img_size[1];

        // image dimension
        $split_img_dimension = explode('x', $config['IMAGE_DIMENSION']);
        $split_img_variation = explode('~', $split_img_dimension[1]);
        $config['IMAGE_WIDTH'] = $split_img_dimension[0];
        $config['IMAGE_HEIGHT'] = $split_img_variation[0];
        $config['IMAGE_DIMENSION_PERCENT'] = $split_img_variation[1];

        //image max/min width and height
        $config['IMAGE_MIN_WIDTH'] = $split_img_dimension[0] - (($split_img_dimension[0] * $split_img_variation[1]) / 100);
        $config['IMAGE_MAX_WIDTH'] = $split_img_dimension[0] + (($split_img_dimension[0] * $split_img_variation[1]) / 100);

        $config['IMAGE_MIN_HEIGHT'] = $split_img_variation[0] - (($split_img_variation[0] * $split_img_variation[1]) / 100);
        $config['IMAGE_MAX_HEIGHT'] = $split_img_variation[0] + (($split_img_variation[0] * $split_img_variation[1]) / 100);

        //========================= image config end =====================
        // for doc file
        $config['DOC_DIMENSION'] = Configuration::where('caption', 'DOC_IMAGE_SIZE')->pluck('value');
        $config['DOC_SIZE'] = Configuration::where('caption', 'DOC_IMAGE_SIZE')->pluck('value2');

        // Doc size
        $split_doc_size = explode('-', $config['DOC_SIZE']);
        $config['DOC_MIN_SIZE'] = $split_doc_size[0];
        $config['DOC_MAX_SIZE'] = $split_doc_size[1];

        // doc dimension
        $split_doc_dimension = explode('x', $config['DOC_DIMENSION']);
        $split_doc_variation = explode('~', $split_doc_dimension[1]);
        $config['DOC_WIDTH'] = $split_doc_dimension[0];
        $config['DOC_HEIGHT'] = $split_doc_variation[0];
        $config['DOC_DIMENSION_PERCENT'] = $split_doc_variation[1];

        //doc max/min width and height
        $config['DOC_MIN_WIDTH'] = $split_doc_dimension[0] - (($split_doc_dimension[0] * $split_doc_variation[1]) / 100);
        $config['DOC_MAX_WIDTH'] = $split_doc_dimension[0] + (($split_doc_dimension[0] * $split_doc_variation[1]) / 100);

        $config['DOC_MIN_HEIGHT'] = $split_doc_variation[0] - (($split_doc_variation[0] * $split_doc_variation[1]) / 100);
        $config['DOC_MAX_HEIGHT'] = $split_doc_variation[0] + (($split_doc_variation[0] * $split_doc_variation[1]) / 100);

        return $config;
    }

    public static function updateScriptPara($sql, $data)
    {
        $start = strpos($sql, '{$');
        while ($start > 0) {
            $end = strpos($sql, '}', $start);
            if ($end > 0) {
                $filed = substr($sql, $start + 2, $end - $start - 2);
                $sql = substr($sql, 0, $start) . $data[$filed] . substr($sql, $end + 1);
            }
            $start = strpos($sql, '{$');
        }
        return $sql;
    }

    public static function getUserTypeName()
    {
        if (Auth::user()) {
            $user_type_id = Auth::user()->user_type;
            $user_type_name = UserTypes::where('id', $user_type_id)
                ->pluck('type_name');
            return $user_type_name;
        } else {
            CommonFunction::redirectToLogin();
        }
    }

//    public static function getUserDeskIds() {
//        if (Auth::user()) {
//            $desk_id = Auth::user()->desk_id;
//            $desk_name = UserDesk::where('desk_id', $desk_id)->pluck('desk_name');
//            return $desk_name;
//        } else {
//            return '';
//        }
//    }
//    public static function getUserDeskName() {
//        if (Auth::user()) {
//            $desk_id = Auth::user()->desk_id;
//            $desk_name = UserDesk::where('desk_id', $desk_id)->pluck('desk_name');
//            return $desk_name;
//        } else {
//            return '';
//        }
//    }

    public static function getDeskName($desk_id)
    {
        if (Auth::user()) {
            $desk_name = UserDesk::where('id', $desk_id)->pluck('desk_name');
            return $desk_name;
        } else {
            return '';
        }
    }

    public static function getCompanyNameById($id)
    {
        if ($id) {
            $name = CompanyInfo::where('id', $id)->pluck('company_name');
            return $name;
        } else {
            return 'N/A';
        }
    }

    public static function getParkNameById($id)
    {
        if ($id) {
            $name = ParkInfo::where('id', $id)->pluck('park_name');
            return $name;
        } else {
            return 'N/A';
        }
    }


    public static function getIndustryCatNameById($id)
    {
        if ($id) {
            $name = IndustryCategories::where('id', $id)->pluck('name');
            return $name;
        } else {
            return 'N/A';
        }
    }


//    send sms or email
    public static function sendMessageFromSystemOld($param)
    {

        $mobileNo = $param[0]['mobileNo'] == '' ? '0' : $param[0]['mobileNo'];
        $smsYes = $param[0]['smsYes'] == '' ? '0' : $param[0]['smsYes'];
        $smsBody = $param[0]['smsBody'] == '' ? '' : $param[0]['smsBody'];
        $emailYes = $param[0]['emailYes'] == '' ? '1' : $param[0]['emailYes'];
        $emailBody = $param[0]['emailBody'] == '' ? '' : $param[0]['emailBody'];
        $emailHeader = $param[0]['emailHeader'] == '' ? '0' : $param[0]['emailHeader'];
        $emailAdd = $param[0]['emailAdd'] == '' ? 'base@gmail.com' : $param[0]['emailAdd'];
        $template = $param[0]['emailTemplate'] == '' ? '' : $param[0]['emailTemplate'];
        $emailSubject = $param[0]['emailSubject'] == '' ? '' : $param[0]['emailSubject'];

        if ($emailYes == 1) {
            $email = $emailAdd;
            $data = array(
                'header' => $emailHeader,
                'param' => $emailBody
            );
            \Mail::send($template, $data, function ($message) use ($email, $emailSubject) {
                $message->from('no-reply@OCPL.gov.bd', 'OSS Framework');
                $message->to($email);
                $message->subject($emailSubject);
            });
        }

//        $smsYes = 1;
        if ($smsYes == 1) {
            $sms = $smsBody;
            $sms = str_replace(" ", "+", $sms);
            //        $sms = str_replace("<br>", "%0a", $sms);
            $mobileNo = str_replace("+88", "", "$mobileNo");

            $url = "http://202.4.119.45:777/syn_sms_gw/index.php?txtMessage=$sms&msisdn=$mobileNo&usrname=business_automation&password=bus_auto@789_admin";
//            echo $url;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($curl);
            curl_close($curl);

        }
        return true;
    }

    public static function sendMessageFromSystem($param = array())
    {
        if (isset(Auth::user()->id)) {
            $userID = Auth::user()->id;
        } else {
            $userID = '';
        }

        $mobileNo = (empty($param[0]['mobileNo']) ? '0' : $param[0]['mobileNo']);
        $smsYes = (empty($param[0]['smsYes']) ? '0' : $param[0]['smsYes']);
        $smsBody = (empty($param[0]['smsBody']) ? 'No SMS Body' : $param[0]['smsBody']);
        $emailYes = (empty($param[0]['emailYes']) ? '1' : $param[0]['emailYes']);
        $emailBody = (empty($param[0]['emailBody']) ? 'No Email Body' : $param[0]['emailBody']);
        $emailHeader = (empty($param[0]['emailHeader']) ? '0' : $param[0]['emailHeader']);
        $emailAdd = (empty($param[0]['emailAdd']) ? 'base@gmail.com' : $param[0]['emailAdd']);
        $template = (empty($param[0]['emailTemplate']) ? '' : $param[0]['emailTemplate']);
        $emailSubject = (empty($param[0]['emailSubject']) ? 'No Subject' : $param[0]['emailSubject']);

        if ($emailYes == 1) {

            $email_content_html = <<<HERE
          <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>OSS Framework</title>
    <link href='https://fonts.googleapis.com/css?family=Vollkorn' rel='stylesheet' type='text/css'>
    <style type="text/css">
        *{
            font-family: Vollkorn;
        }
    </style>
</head>


<body>
<table width="80%" style="background-color:#D2E0E8;margin:0 auto; height:50px; border-radius: 4px;">
    <thead>
    <tr>
        <td style="padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);">
            
            <h4 style="text-align:center">
              board Meeting
            </h4>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="margin-top: 20px; padding: 15px;">
            <!--Dear Applicant,-->
            Dear User,
            <br/><br/>
          $emailBody

            <br/><br/>
        </td>
    </tr>
    <tr style="margin-top: 15px;">
        <td style="padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);">
            <h5 style="text-align:center">All right reserved by OSS Framework 2018.</h5>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
HERE;

            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0; // there is no service id
            $emailQueue->app_id = 0; // there is no app id
            $emailQueue->email_content = $email_content_html;
            $emailQueue->email_to = $emailAdd;
            $emailQueue->email_subject = $emailSubject;
            $emailQueue->email_cc = '';
            $emailQueue->attachment = '';
            $emailQueue->secret_key = '';
            $emailQueue->pdf_type = '';
            $emailQueue->user_id = $userID;
            $emailQueue->save();
        }
        if ($smsYes == 1) {
            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0; // there is no service id
            $emailQueue->app_id = 0; // there is no app id
            $emailQueue->user_id = $userID; // there is no app id
            $emailQueue->sms_content = $smsBody;
            $emailQueue->sms_to = $mobileNo;
            $emailQueue->attachment = '';
            $emailQueue->secret_key = '';
            $emailQueue->pdf_type = '';
            $emailQueue->save();
        }
    }

    public static function report_gen($id, $data, $report_title, $link = '', $heading = '')
    {
        $dataTablePara = '';
        $showaction = false;
        $cols = array();
        $count = 0;
        if ($link) {
            $json_data = json_decode($link);
            if (!empty($json_data)) {
                foreach ($json_data as $jrow) {
                    if ($jrow->type == 'link') {
                        $showaction = true;
                    } else if ($jrow->type == 'dataTable') {
                        $dataTablePara = $jrow->properties;
                    } else if ($jrow->type == 'column') {
                        $cols[$jrow->ID]['caption'] = $jrow->caption;
                        $cols[$jrow->ID]['style'] = $jrow->style;
                    } else {
                        $showaction = true;
                    }
                }
            }
        }
        ?>
        <div class="graph_box">
            <?php if ($heading) { ?>
                <div class="report_heading">
                    <div><?php echo $heading; ?></div>
                </div>
            <?php } ?>
            <?php if (count($data) > 0) { ?>
                <table id="report_data" class="table-rpt-border table table-responsive table-condensed">
                    <thead>
                    <tr>
                        <?php
                        foreach ($data[0] as $key => $value) {
                            echo '<th';
                            if (isset($cols[$key]['style']))
                                echo ' style="' . $cols[$key]['style'] . '"';
                            echo '>';
                            echo isset($cols[$key]['caption']) ? $cols[$key]['caption'] : $key;
                            echo '</th>';
                        }
                        if ($showaction) {
                            echo '<th>Action</th>';
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sl = 0;
                    foreach ($data as $row):
                        $rowdata = array();
                        if ($sl % 2 == 0) {
                            $row_bg_color = 'style="background-color:#FAFAFA"';
                        } else {
                            $row_bg_color = 'style=""';
                        }
                        if ($count >= 250) {
                            echo '<tfoot><tr><td colspan="5"><b>Showing ' . 250 . ' rows out of total ' . count($data) . '! Please export as CSV to show all data.</b></td></tr></tfoot>';
                            break;
                        }
                        $count++;
                        ?>
                        <tr <?php echo $row_bg_color; ?>>
                            <?php
                            foreach ($row as $key => $field_value):
                                //echo '<td>';
                                $td_align = is_numeric($field_value) ? 'text-align:center;' : '';
                                echo '<td';
                                if (isset($cols[$key]['style']))
                                    echo ' style="' . $cols[$key]['style'] . ';"';
                                echo '>';
                                echo formatTDValue($field_value);
//                                if (is_numeric($field_value)) {
//                                    echo '<span style="text-align:center;width:100%;float: left;">' . $field_value . '&nbsp;</span>';
//                                } else {
//                                    echo $field_value . '&nbsp;';
//                                }
                                echo '</td>';
                                if ($link) {
                                    $rowdata[$key] = $field_value;
                                }
                            endforeach;
                            if ($showaction) {
                                echo '<td>';
                                foreach ($json_data as $jrow) {
                                    if ($jrow->type == 'link') {
                                        $rowdata['baseurl'] = base_url();
                                        echo '<a href="' . ConvPara($jrow->url, $rowdata) . '">' . $jrow->caption . '</a>&nbsp;';
                                    } else if ($jrow->type == 'dataTable') {

                                    } else {
                                        print_r($jrow);
                                    }
                                }
                                echo '</td>';
                            }
                            ?>
                        </tr>
                        <?php
                        $sl++;
                    endforeach;
                    if ($count <= 250) {
                        echo '<tfoot><tr><td colspan="5">Showing ' . $count . ' rows out of total ' . count($data) . ' Records</td></tr></tfoot>';
                    } ?>
                    </tbody>
                </table>

                <?php
            } else {
                echo '<h4 style="text-align: center;color: gray">Data Not Found!</h4>';
            }
            ?>
        </div>

        <?php
        return $count;
    }

    public static function lastAction()
    {
        $lastAction = ActionInformation::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->limit(3)->get();
        return $lastAction;
    }

    public static function pendingApplication()
    {
        $pendingApplication = ProcessList::where('desk_id', '=', CommonFunction::getDeskId())->count();
        return $pendingApplication;
    }

    public static function requestPinNumber()
    {
        $email_queue_id = \Session::get('email_queue_id');
        $users = Users::where('id', CommonFunction::getUserId())->first(['user_email', 'user_phone']);
        $emailAndSms = EmailQueue::where('id', $email_queue_id)->orderby('id', 'DESC')->first(['email_to', 'sms_to']);
        $code = rand(1000, 9999);
        $token = $code . '-' . CommonFunction::getUserId();
        $encrypted_pin = Encryption::encode($token);
        Users::where('user_email', $users->user_email)->update(['pin_number' => $encrypted_pin]);

        $body_msg = "Pin Number for application process: <strong><code>" . $code . "</code></strong>";

//        $emailYes='';
//        $smsYes='';
//        if($emailAndSms->email_to!=''){
//            $emailYes =  '1';
//        }elseif($emailAndSms->sms_to!=''){
//            $smsYes =  '1';
//        }else{
//            $emailYes =  '1';
//            $smsYes =  '1';
//        }
        $emailYes = '1';
        $smsYes = '1';
        $params = array([
            'emailYes' => $emailYes,
            'emailTemplate' => 'Users::message',
            'emailBody' => $body_msg,
            'emailSubject' => 'OCPL OSS Framework',
            'emailHeader' => 'Process Pin Number',
            'emailAdd' => $users->user_email,
            'mobileNo' => $users->user_phone,
            'smsYes' => $smsYes,
            'smsBody' => 'Pin Number for application process:' . $code,
        ]);
        CommonFunction::sendMessageFromSystem($params);
        return true;
    }

    public static function alreadyAdded($process_id, $agenda_id = 0)
    {
        $boardMeting = ProcessListBoardMeting::where('process_id', $process_id)->where('is_archive', 0)->first();
//        $boardMeting =  ProcessListBoardMeting::where('process_id', $process_id)->where('agenda_id', $agenda_id)->where('is_archive', 0)->first();
        if ($boardMeting) {
            $a = 1;
        } else {
            $a = 0;
        }
        return $a;
    }

    public static function alreadyAddedAgenda($agenda_id)
    {
        $boardMeting = ProcessListBoardMeting::where('agenda_id', $agenda_id)->first();
        if ($boardMeting) {
            $a = 1;
        } else {
            $a = 0;
        }
        return $a;
    }
    public static function checkChairperson($board_meeting_id)
    {
        $boardMeting = Committee::where('board_meeting_id', $board_meeting_id)->where('type' ,'=' ,'yes')->first();
        return $boardMeting->user_email;
    }

    public static function getBoardMeetingInfo($ref_id)
    {
        $board_meeting_id = Encryption::decodeId(Session::get('board_meeting_id'));
        $agenda_id = Encryption::decodeId(Session::get('agenda_id'));
        $app_id = Encryption::decodeId($ref_id);

        $boardMeetingInfo = BoardMeting::leftJoin('board_meeting_process_status', 'board_meeting_process_status.id', '=', 'board_meting.status')
            ->where('board_meting.id', $board_meeting_id)
            ->first(['board_meting.*', 'board_meeting_process_status.status_name', 'board_meeting_process_status.panel']);

        $agendaInfo = Agenda::leftJoin('process_type', 'process_type.id', '=', 'agenda.process_type_id')
            ->leftJoin('board_meeting_process_status', 'board_meeting_process_status.id', '=', 'agenda.status')
            ->where('agenda.id', $agenda_id)
            ->first(['agenda.*', 'process_type.name as process_name',
                'board_meeting_process_status.status_name', 'board_meeting_process_status.id as status_id', 'board_meeting_process_status.panel']);

        if ($boardMeetingInfo->status == 11) { // 11= board meeting publish
            $chairmanRemarks = ProcessList::leftJoin('process_list_board_meeting', 'process_list.id', '=', 'process_list_board_meeting.process_id')
                ->where('ref_id', $app_id)
                ->first(['process_list_board_meeting.bm_remarks']);
        } else {
            $chairmanRemarks = '';
        }

        $data = ['agenda_info' => $agendaInfo, 'board_meeting_info' => $boardMeetingInfo, 'chairmanRemarks' => $chairmanRemarks];
        return $data;
    }


    public static function getMemberRemarks($id)
    {
        $bm_process = ProcessListBMRemarks::where('bm_process_id', $id)->where('user_id', CommonFunction::getUserId())->orderBy('id', 'desc')->first();

        if (count($bm_process) > 0) {
            return $bm_process->remarks;
        } else {
            return $bm_process = "";
        }
    }

    public static function getSequenceNo($board_meeting_id)
    {
        $id = Encryption::decodeId($board_meeting_id);
        if ($id == 1) {
            $sequence_no = 1;
        } else {
            $sequence_no = BoardMeting::where('id', $id)->first()->sequence_no;
        }
        return $sequence_no;
    }

    public static function checkProfileInfo()
    {
        $user_id = CommonFunction::getUserId();
        $userInfo = Users::find($user_id);
//        $userInfo->division == ''
        if ($userInfo->user_full_name == '' || $userInfo->user_DOB == ''
            || $userInfo->user_phone == '' || $userInfo->country == ''
            || $userInfo->nationality == '' || $userInfo->user_pic == ''
            || $userInfo->district == ''
            || $userInfo->road_no == ''
        ) {

//
//            if ($userInfo->user_nid == null && $userInfo->passport_no == null) {
                return false;
            }else{
                return true;
            }
//        }
    }

    public static function asciiCharCheck($value){
        if(mb_detect_encoding($value, 'ASCII', true)){
            return true; // no ascii not found
        }else{
            return false;
//            Session::flash('error', 'non-ASCII Characters in main_business_objective [BI-1023]');
//            return redirect('licence-applications/company-registration/add#step2');

        }
    }

    public function entryAccessLog()
    {
        // access_log table.
        $str_random = str_random(10);
        $insert_id = DB::table('user_logs')->insertGetId(
            array(
                'user_id' => Auth::user()->id,
                'login_dt' => date('Y-m-d H:i:s'),
                'ip_address' => \Request::getClientIp(),
                'access_log_id' => $str_random
            )
        );

        Session::put('access_log_id', $str_random);
    }

    /*     * ****************************End of Class***************************** */
}
