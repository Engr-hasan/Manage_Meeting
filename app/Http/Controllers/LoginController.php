<?php

namespace App\Http\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\ProcessPath\Models\UserDesk;
use App\Modules\Settings\Models\HomePageSlider;
use App\Modules\Settings\Models\Notice;
use App\Modules\Settings\Models\ServiceDetails;
use App\Modules\Settings\Models\WhatsNew;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\UserTypes;
use App\Modules\Users\Models\UsersModel;
use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\FailedLogin;
use App\Modules\Users\Models\SecurityProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use App\Modules\Web\Controllers\WebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function __construct() {

        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
    }

    public function index($lang='')
    {
        App::setLocale($lang);
        Session::set('lang', $lang);
//        return redirect('login/'.$lang);

        CommonFunction::GlobalSettings();
        $list = null;
//        $noticeData = WebController::getDashboardObjectsJson('NOTICE_PUBLIC');
//        $notice = [];
//        if ($noticeData) {
//            $noticeDecodedData = json_decode($noticeData);
//            $notice = $noticeDecodedData->json;
//        }
//        dd($noticeDecodedData);
        $notice = Notice::
        where('status', 'public')
            ->where('is_active',1)
            ->orderBy('notice.updated_at', 'desc')
            ->limit(10)
            ->get(['id', 'heading','prefix', 'details', 'importance', 'status', 'updated_at as update_date']);

        $trainingData = DB::table('trainings')->where('user_types', 'like', '%public%')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
        $dynamicSection = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
            ->where('service_details.status',1)
            ->orderby('id','DESC')->get(['pt.name','pt.id']);
        $home_slider_image = HomePageSlider::where('status',1)->orderby('id','DESC')->take(5)->get();
        $whatsNew = WhatsNew::where('is_active',1)->orderby('id','DESC')->take(5)->get();
        return view('home', compact('list', 'notice','trainingData','dynamicSection','whatsNew','home_slider_image'));
    }
    /*
     * Login process check function
     */
    public function reCaptcha() {
        return Captcha::img();
    }
    public function singleNotice($id){
        $nid = Encryption::decodeId($id);
        $nData  = Notice::find($nid);
        return view('public_home.singleNotice',compact('nData'));
    }
    public function check(Request $request, Users $usersModel )
    {

//            $rules = [
//                'email' => 'required|email',
//                'password' => 'required|max:30',
//            ];

        if (Session::get('hit') >= 3) {

            $rules = [
                'email' => 'required|email',
                'password' => 'required|max:30',
                'captcha' => 'required|captcha',
            ];

            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $data = ['responseCode' => 0, 'msg' => 'Invalid Captcha Code','redirect_to' => ''];
                return response()->json($data);
            }

        }
        else{
            $rules = [
                'email' => 'required|email',
                'password' => 'required|max:30',
            ];
            $this->validate($request, $rules);
        }





            if (!$this->_checkAttack($request))
            {
                $msg= Session::get("error");
                Session::flash('error', 'Invalid login information!![HIT3TIMES]');
                $data = ['responseCode' => 0, 'msg' => $msg,'redirect_to' => ''];
            }
            else
            {
                $response = $this->commonLoginCheck($request,$usersModel,1,'',true);
                if($response['result'])
                {
                    Session::flash('success', $response['msg']);
                    $data = ['responseCode' => 1, 'msg' => $response['msg'],'redirect_to' => $response['redirect_to']];
                }
                else
                {
                    Session::flash('error', $response['msg']);
                    $data = ['responseCode' => 0, 'msg' => $response['msg'],'redirect_to' => $response['redirect_to']];
                }
            }
            return response()->json($data);
    }

    /*
     * check for attack
     */
    private function _checkAttack($request)
    {
        try {
            $ip_address = $request->ip();
            $user_email = $request->get('email');
            $count = FailedLogin::where('remote_address', "$ip_address")
                ->where('is_archive', 0)
                ->where('created_at', '>', DB::raw('DATE_ADD(now(),INTERVAL -20 MINUTE)'))
                ->count();
            if ($count > 20) {
                Session::flash('error', 'Invalid Login session. Please try after 10 to 20 minute [LC6091], Please contact with system admin.');
                return false;
            } else {
                $count = FailedLogin::where('remote_address', "$ip_address")
                    ->where('is_archive', 0)
                    ->where('created_at', '>', DB::raw('DATE_ADD(now(),INTERVAL -60 MINUTE)'))
                    ->count();
                if ($count > 40) {
                    Session::flash('error', 'Invalid Login session. Please try after 30 to 60 minute [LC6092], Please contact with system admin.');
                    return false;
                } else {
                    $count = FailedLogin::where('user_email', $user_email)
                        ->where('is_archive', 0)
                        ->where('created_at', '>', DB::raw('DATE_ADD(now(),INTERVAL -10 MINUTE)'))
                        ->count();
                    if ($count > 6) {
                        Session::flash('error', 'Invalid Login session. Please try after 5 to 10 minute 1002, Please contact with system admin.');
                        return false;
                    }
                }
            }

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine());
            Session::flash('error', 'Login session exception. Please try after 5 to 10 minute 1003, Please contact with system admin.');
            return false;
        }
        return true;
    }

    public static function killUserSession($user_id)
    {
        $sessionID = Users::where('id', $user_id)->pluck('login_token');
        if (!empty($sessionID)) {
            $sessionID = Encryption::decode($sessionID);
            Session::getHandler()->destroy($sessionID);
        }
        Users::where('id', $user_id)->update(['login_token' => '']);
    }

    public function _checkSecurityProfile($request, $ip_param = '')
    {
        $security_id = Auth::user()->security_profile_id;
        if ($security_id == 0 || $security_id == '') {
            $security_id = UserTypes::where('id', Auth::user()->user_type)->first()->security_profile_id;
        }
        if ($security_id) {
            $security = SecurityProfile::where(['id' => $security_id, 'active_status' => 'yes'])->first();
            if ($security) {
                if ($ip_param) {
                    $ip = $ip_param;
                } else {
                    $ip = $request->ip();
                }
                if ($ip == '127.0.0.1' || $ip == '::1') {
                    $ip = '0.0.0.0';
                }
                $net = '0.0.0.0';
                $nets = explode('.', $ip);
                $weekName = strtoupper(date('D'));
                if (count($nets) == 4) {
                    $net = $nets[0] . '.' . $nets[1] . '.' . $nets[2] . '.0';
                }
                if ($security->allowed_remote_ip == '' || $security->allowed_remote_ip == '0.0.0.0' || !(strpos($security->allowed_remote_ip, $net) === false) || !(strpos($security->allowed_remote_ip, $ip) === false)) {
                    if (strpos($security->week_off_days, $weekName) === false) {
                        if (time() >= strtotime($security->work_hour_start) && time() <= strtotime($security->work_hour_end)) {
                            return true;
                        }
                    }
                }
            }
        }
        return true;
    }

    /*
     * protect login for special types users
     */
    private function _protectLogin($type = false)
    {
        if ($type == '10x414') {// For UDC users
            Auth::logout();
            Session::flash('error', 'This url do not allow your access.');
            return false;
        } else {
            return true;
        }
    }

    /*
     * Insert login info in user_logs table
     */
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

    /*
     * Store all failed login history
     */
    private function _failedLogin($request)
    {
        $ip_address = $request->ip();
        $user_email = $request->get('email');
        FailedLogin::create(['remote_address' => $ip_address, 'user_email' => $user_email]);
    }

    /*
     * Caption set up
     */
    private function _setCaption($usersModel)
    {
        /*
         * for user caption (like: Bank name/agency name/udc name etc)
         */

        $caption_name = '';
        $userAdditionalInfo = $usersModel->getUserSpecialFields(Auth::user());
        if (count($userAdditionalInfo) >= 1 && $userAdditionalInfo[0]['value']) {
            $caption_name .= ' - ';
            if (Auth::user()->user_type == '7x711' || Auth::user()->user_type == '7x712' || Auth::user()->user_type == '7x713') {
                $caption_name .= UserTypes::where('id', Auth::user()->user_type)->pluck('type_name') . ', ';
            }

            $caption_name .= $userAdditionalInfo[0]['value']; //$userAdditionalInfo[0]['caption'] . ': ' .
            if (strlen($caption_name) > 45) {
                $caption_name = substr($caption_name, 0, 43) . '..';
            }
        } else {
            $caption_name .= ' - ' . Auth::user()->user_email;
        }
        Session::put('caption_name', $caption_name);
    }

    /*
     * User's session set up
     */
    private function _setSession()
    {
        try
        {
            if (Auth::user()->is_approved == 1 && Auth::user()->user_status == 'active')
            {
                Session::put('lang', Auth::user()->user_language);
                App::setLocale(Session::get('lang'));
                Session::put('hit', 0);

                //for user report module
                Session::put('sess_user_id', Auth::user()->id);
                Session::put('sess_user_type', Auth::user()->user_type);
                Session::put('sess_user_sub_type', Auth::user()->user_sub_type);
                Session::put('sess_district', Auth::user()->district);
                Session::put('sess_thana', Auth::user()->thana);

                // To set user desk
                $my_desk_ids = Users::where('id', Auth::user()->id)->pluck('desk_id');
                $my_desk_ids_exploded = explode(',', $my_desk_ids);


                // Get the users who have delegated to me
//                $delegated_users_to_me = UsersModel::where('delegate_to_user_id', Auth::user()->id)->lists('id')->all();

                // Get the delegated desks by delegated users
//                $delegated_desk_to_me = UserDesk::whereIn('user_id', $delegated_users_to_me)->first([DB::raw('group_concat(desk_id) as user_desk')]);

//                $delegated_desk_to_me_exploded = array();
//                if ($delegated_desk_to_me->user_desk != null) {
//                    $delegated_desk_to_me_exploded = explode(',', $delegated_desk_to_me->user_desk);
//                }

                $all_desk_to_me = implode(',', array_unique($my_desk_ids_exploded));
                Session::put('user_desk_ids', $all_desk_to_me);
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Invalid session ID!');
            return false;
        }
        return true;
    }

    /*
     * Entry access for Logout
     * update logout time in user_logs table
     */
    public function entryAccessLogout()
    {
        $access_log_id = Session::get('access_log_id');
        DB::table('user_logs')->where('access_log_id', $access_log_id)->update(['logout_dt' => date('Y-m-d H:i:s')]);
    }

    public function logout()
    {
        if (Auth::user())
        {
            UsersModel::where('id', Auth::user()->id)->update(['login_token' => '']);
        }
        $this->entryAccessLogout();
        Session::getHandler()->destroy(Session::getId());
        Session::flush();
        Auth::logout();
        return redirect('/login');
    }



    public function loadLoginForm()
    {
        return strval(view('public_home.login-credential'));
    }
    public function loadLoginOtpForm()
    {
        return strval(view('public_home.otp'));
    }


    public function otpLoginEmailValidationWithTokenProvide(Request $request)
    {
        $email = trim($request->get('email'));
        $otpBy = trim($request->get('otp'));
//        dd($otpBy);

        /*
         * User given data is OK
         */
        if($email && $otpBy)
        {
            $user = Users::where('user_email', $email)
                ->where('is_approved', '=', 1)
                ->where('user_status', '=', 'active')->first();

            /*
             * User is valid
             */
            if($user) {
                $login_token = rand(1111, 9999);
                $otp_expire_time = strtotime("+10 minutes", time());
                Users::where('id', $user->id)->update(['login_token' => $login_token]);

                $data = ['responseCode' => 1, 'data' => 'Valid email'];
                // sms or email for otp

                if ($otpBy == 1){
                    $params = array([
                        'emailYes' => '0',
                        'emailTemplate' => 'Users::message',
                        'emailBody' => $login_token,
                        'emailSubject' => 'OTP login information',
                        'emailHeader' => 'OTP login information',
                        'emailAdd' => 'shahin.fci@gmail.com',
                        'mobileNo' => $user->user_phone,
                        'smsYes' => '1',
                        'smsBody' => "Your login OTP for <Project Name>: " . $login_token,
                    ]);
                CommonFunction::sendMessageFromSystem($params);
            }
            else {
                $params = array([
                    'emailYes' => '1',
                    'emailTemplate' => 'Users::message',
                    'emailBody' => $login_token,
                    'emailSubject' => 'OTP login information',
                    'emailHeader' => 'OTP login information',
                    'emailAdd' => $user->user_email,
                    'mobileNo' => '01767957180',
                    'smsYes' => '0',
                    'smsBody' => "Your login OTP: " . $login_token,
                ]);
                CommonFunction::sendMessageFromSystem($params);
            }
                return response()->json($data);
            }
            else
            {
                $data = ['responseCode' => 0, 'data' => 'Invalid email'];
                return response()->json($data);
            }
        }
        else
        {
            $data = ['responseCode' => 0, 'data' => 'Invalid email'];
            return response()->json($data);
        }
    }

    public function checkOtpLogin(Request $request,Users $usersModel)
    {
        $rules = [
            'email' => 'required|email',
            'login_token' => 'required',
        ];
        if (Session::get('hit') >= 3) {
            $rules = ['captcha' => 'required|captcha'];
        }
        $messages = [
            'captcha' => 'Invalid :attribute code'
        ];
        $this->validate($request, $rules, $messages);

        if (!$this->_checkAttack($request))
        {
            Session::flash('error', 'Invalid login information!![HIT3TIMES]');
            $data = ['responseCode' => 0, 'msg' => '','redirect_to' => ''];
        }
        else
        {
            $response = $this->commonLoginCheck($request,$usersModel,2,trim($request->get('login_token')),true);
            if($response['result'])
            {
                Session::flash('success', $response['msg']);
                $data = ['responseCode' => 1, 'msg' => $response['msg'],'redirect_to' => $response['redirect_to']];
            }
            else
            {
                Session::flash('error', $response['msg']);
//                $data = ['responseCode' => 0, 'msg' => $response['msg'],'redirect_to' => $response['redirect_to']];
                $data = ['responseCode' => 0, 'msg' => "Invalid OTP",'redirect_to' => $response['redirect_to']];
            }
        }
        return response()->json($data);
    }

    /*
     * loginType (1) = Login By Credential
     * loginType (2) = Login By OTP
     */
    private function commonLoginCheck($request,$usersModel,$loginType=0,$otp='',$is_ajax_request=false)
    {
        try
        {
            if($loginType==1) // General login
            {
                $loggedin = Auth::attempt(['user_email' => $request->get('email'),'password' => $request->get('password')]);
            }
            else if($loginType==2) // Login with OTP
            {
                $user = $usersModel::where('user_email', $request->get('email'))->where('login_token', $otp)->first();
                if (empty($user))
                {
                    $response = array('result'=>false,'msg'=>'Invalid login information' ,'redirect_to'=>'','is_ajax_request'=>$is_ajax_request);
                    return $response;
                }


                $loggedin = Auth::loginUsingId($user->id);

                if(!$loggedin)
                {
                    $response = array('result'=>false,'msg'=>'Login failed. Please reload the page and try again.' ,'redirect_to'=>'','is_ajax_request'=>$is_ajax_request);
                    return $response;
                }
            }
            if($loggedin)
            {
                $userTypeRootStatus = $this->_checkUserTypeRootActivation(Auth::user()->user_type, Auth::user()->user_sub_type,$is_ajax_request);
                if ($userTypeRootStatus['result'] == false) {
                    Auth::logout();
                    $response = array('result' => false, 'msg' => $userTypeRootStatus['msg'], 'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
                    return $response;
                }
              $this->killUserSession(Auth::user()->id);
                Users::where('id', Auth::user()->id)->update(['login_token' => Encryption::encode(Session::getId())]);

                if (!in_array(Auth::user()->user_type,['1x101','2x202'])) {
                    if (Auth::user()->delegate_to_user_id != 0) {
                        Session::put('sess_delegated_user_id', Auth::user()->delegate_to_user_id);
                        $response = array('result'=>true,'msg'=>'Logged in successfully, Welcome to '.env('PROJECT_NAME') ,'redirect_to'=>'/users/delegate','is_ajax_request'=>$is_ajax_request);
                        return $response;
                    }
                }
                if (Auth::user()->is_approved == 1 && Auth::user()->user_status != 'active') {
                    Auth::logout();
                    $response = array('result'=>false,'msg'=>'The user is not active, please contact with system admin.' ,'redirect_to'=>'','is_ajax_request'=>$is_ajax_request);
                    return $response;
                }


                if (!$this->_checkSecurityProfile($request)) {
                    Auth::logout();
                    $response = array('result'=>false,'msg'=>'Security profile does not support login from this network or time [SP2001]' ,'redirect_to'=>'','is_ajax_request'=>$is_ajax_request);
                    return $response;
                }


                $loginAccess = $this->_protectLogin(Auth::user()->user_type); //login protected for UDC
                if ($loginAccess == false)
                {
                    //For any user type we can protect login from here
                    $response = array('result'=>false,'msg'=>'This user is inactive or restricted!' ,'redirect_to'=>'/login','is_ajax_request'=>$is_ajax_request);
                    return $response;
                }

                ACL::db_reconnect();
                $this->entryAccessLog();

                $this->_setCaption($usersModel);
                if ($this->_setSession() == false)
                {
                    $response = array('result'=>false,'msg'=>'Session expired' ,'redirect_to'=>'/login','is_ajax_request'=>$is_ajax_request);
                    return $response;
                }

                if (Auth::user()->first_login == 0 || Auth::user()->is_approved != 1)
                {
                    if (Auth::user()->first_login == 0)
                    {
                        UsersModel::where('id', Auth::user()->id)->update(['first_login' => 1]);
                    }
                    //return redirect()->intended('/users/profileinfo')->with('success', trans('messages.welcome'));
                    $response = array('result'=>true,'msg'=>trans('messages.welcome') ,'redirect_to'=>'/users/profileinfo','is_ajax_request'=>$is_ajax_request);
                    return $response;
                }
                else
                {

                    $user_type = UserTypes::where('id', Auth::user()->user_type)->first();
                    if (($user_type->auth_token_type == 'mandatory') || ($user_type->auth_token_type == 'optional' && Auth::user()->auth_token_allow == 1))
                    {
                        UsersModel::where('id', Auth::user()->id)->update(['auth_token' => 'will get a code soon']);
                        //return redirect()->intended('/users/two-step')->with('success', 'Logged in successfully, Please verify the 2nd steps.');
                        $response = array('result'=>true,'msg'=>'Logged in successfully, Please verify the 2nd steps.' ,'redirect_to'=>'/users/two-step','is_ajax_request'=>$is_ajax_request);
                        return $response;
                    }
                    else
                    {
                        //return redirect()->intended('/dashboard')->with('success', 'Logged in successfully, Welcome to OCPL Base');
                        CommonFunction::GlobalSettings();
                        $response = array('result'=>true,'msg'=>'Logged in successfully, Welcome to '.env('PROJECT_NAME') ,'redirect_to'=>'/dashboard','is_ajax_request'=>$is_ajax_request);
                        return $response;
                    }
                }
            }
            else
            {
                if(Session::has('hit'))
                {
                    Session::put('hit', Session::get('hit') + 1);
                }
                else
                {
                    Session::put('hit', 1);
                }
                $this->_failedLogin($request);
                $response = array('result'=>false,'msg'=>'Invalid email or password' ,'redirect_to'=>'','is_ajax_request'=>$is_ajax_request);
                return $response;
            }
        }
        catch(\Exception $e)
        {
            Auth::logout();
            $response = array('result'=>false,'msg'=>'Something went wrong' ,'redirect_to'=>'/login','is_ajax_request'=>$is_ajax_request);
            return $response;
        }
    }
    private function _checkUserTypeRootActivation($userType = null, $userSubType = null,$is_ajax_request) {
        $typeArr = explode('x', $userType);
        $userTypeId = $typeArr[0];
        // for checking user type status
        $userTypeInfo = UserTypes::where('id', $userType)->first();
        
        if ($userTypeInfo->status != "active") {
            Auth::logout();
           // Session::flash('error', 'The user is not active, please contact with system admin');
            $response = array('result'=>false,'msg'=>'The user is not active, please contact with system admin.' ,'redirect_to'=>'','is_ajax_request'=>$is_ajax_request);
            return $response;
        }
        if($userType=="5x505") {

            $company = explode(",",Auth::user()->user_sub_type) ;
            $TotalCompany = count($company);
            $total=0;
            foreach($company as $company_id){

                $CheckActiveCompany = CompanyInfo::where('id', $company_id)->first();

                if ($CheckActiveCompany != "") {
                    if ($CheckActiveCompany->is_approved == 1 || $CheckActiveCompany->company_status == 1) {
                        $total=$total+1;

                    }
                }else{
                    Auth::logout();
                    $response = array('result'=>false,'msg'=>'Your company is not Associated, please contact with system admin. [R00013].' ,'redirect_to'=>'','is_ajax_request'=>$is_ajax_request);
                    return $response;
                }

            }
            if($TotalCompany!=$total){
                Auth::logout();
                $response = array('result'=>false,'msg'=>'Your company is not active, please contact with system admin. [R00013].' ,'redirect_to'=>'','is_ajax_request'=>$is_ajax_request);
                return $response;
            }


        }
       return $response = array('result'=>true);
    }



    public function allClassRoute(){
        $controllers = [];

        foreach (Route::getRoutes()->getRoutes() as $route)
        {
            $action = $route->getAction();

            if (array_key_exists('controller', $action))
            {
                // You can also use explode('@', $action['controller']); here
                // to separate the class name from the method
                $controllers[] = $action['controller'];
            }
        }
        dd($controllers);
    }
    public function type_wise_details(Request $request){
      $data = $request->get('type_id');
        $serviceDetails = ServiceDetails::where('process_type_id',$data)->orderby('id','desc')->first(['login_page_details']);
        $data = ['responseCode' => 1, 'data' => $serviceDetails];
        return response()->json($data);
    }
}


