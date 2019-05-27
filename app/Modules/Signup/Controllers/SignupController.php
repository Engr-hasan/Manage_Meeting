<?php

namespace App\Modules\Signup\Controllers;

//use App\Http\Requests;
//use App\Http\Controllers\Controller;
//
//use Illuminate\Http\Request;







use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Signup\Models\MemberShipNumber;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
#use App\Modules\Users\Models\EconomicZones;
use App\Modules\Users\Models\FailedLogin;
#use App\Modules\Users\Models\UserDesk;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UsersModelEditable;
use App\Modules\Users\Models\UserTypes;
use App\Modules\Users\Models\AreaInfo;
use Illuminate\Http\Request;



use App\Http\Controllers\LoginController;
use App\Http\Requests\profileEditRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;
use yajra\Datatables\Datatables;
use Validator;

class SignupController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        CommonFunction::GlobalSettings();
        return view("Signup::index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $user_types = ['' => 'Select One'] + UserTypes::orderBy('type_name')->where('is_registarable',1)->orderBy('type_name','ASC')->lists('type_name', 'id')->all();
        $countries = Countries::orderby('name')->lists('name', 'iso');
        $nationalities = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
        $divisions = AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id');
        $districts = AreaInfo::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_id');
        return view("Signup::registration", compact("user_types", "nationalities", "countries", "divisions", "districts"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $requestEmail = $request->get('user_email');
        $result = Users::where('user_email', '=', $requestEmail)->first();


//		if ($result)
//		{
//			if ($result->user_verification == 'no')
//			{
//				\Session::flash('verifyNo',"You have previously received a sign up request with this email address, which did not get verified yet.
//											If you did not get any link to verify your email address, please click the 'Resend Email' button and
//											follow the given instructions to complete your sign up process.");
//				return redirect('signup?tmp=' . Encryption::encodeId($request->get('user_email')));
//			}
//			else if ($result->user_verification == 'yes')
//			{
//				\Session::flash('verifyYes', 'Sorry, You have already registered with this email address. Did you forget your password?');
//				return redirect('signup');
//			}
//		}

        if ($result) {

            if ($result->user_status != "active") {
                if (strtotime($result->updated_at) > strtotime("-30 minutes")) {
                    \Session::flash('error', "Your email already taken. You may try after 30 minute again.");
                    return Redirect::back()->withInput();
                }
            }
            else{
                \Session::flash('error', "Your email already taken and you are active user.");
                return Redirect::back()->withInput();
            }

            if ($result->user_verification == 'no') {
                \Session::flash('verifyNo', "You have previously received a sign up request with this email address, which did not get verified yet.
		If you did not get any link to verify your email address, please click the 'Resend Email' button and
		follow the given instructions to complete your sign up process.");
                return redirect('signup?tmp=' . Encryption::encodeId($request->get('user_email')));
            }

        }

        $approve_status = 0;
        $user_status = 'inactive';

        $this->validate($request, [
            'user_full_name' => 'required',
            'user_DOB' => 'required|date',
            'user_phone' => 'required',
            'user_email' => 'required|email|unique:users',
            'country' => 'required',
            'nationality' => 'required',
            'road_no' => 'required',
            'g-recaptcha-response' => 'required'
        ]);

        try{

            $token_no = hash('SHA256', "-" . $request->get('user_email') . "-");
            $encrypted_token = Encryption::encodeId($token_no);
            $data = [
                'user_full_name' => $request->get('user_full_name'),
                'user_DOB' => CommonFunction::changeDateFormat($request->get('user_DOB'), true),
                'user_phone' => $request->get('user_phone'),
                'user_email' => $request->get('user_email'),
                'user_hash' => $encrypted_token,
                'user_type' => $request->get('user_type'),
                'country' => $request->get('country'),
                'nationality' => $request->get('nationality'),
                'identity_type' => $request->get('identity_type'),
                'passport_no' => $request->get('passport_no'),
                'user_nid' => $request->get('user_nid'),
                'division' => $request->get('division'),
                'district' => $request->get('district'),
                'state' => $request->get('state'),
                'province' => $request->get('province'),
                'road_no' => $request->get('road_no'),
                'house_no' => $request->get('house_no'),
                'post_code' => $request->get('post_code'),
                'user_fax' => $request->get('user_fax'),
                'is_approved' => $approve_status,
                'user_status' => $user_status,
                'user_agreement' => 0,
                'first_login' => 0,
                'user_verification' => 'no'
            ];
            Users::create($data);
            if($this->signupMail($request,$encrypted_token))
            {
                return redirect('signup?tmp=' . Encryption::encodeId($request->get('user_email')));
            }
            else
            {
                return Redirect::back()->withInput();
            }
        }
        catch (\Exception $e)
        {
            Session::flash('error', 'Something went wrong[SIGNUP1001]');
            return Redirect::back()->withInput();
        }
    }

    public function GoogleStore(Request $request)
    {
        $result=Auth::user();
        $approve_status = 0;
        $rules=[
            'user_full_name' => 'required',
            'user_DOB' => 'required|date',
            'district' => 'required',
            'division' => 'required',
        ];

        if ($request->get('company_type') == 1) { // existing company
            $rules['company_id'] = 'required';
        } else { // new company
            $rules['company_name'] = 'required|regex:/^[a-zA-Z\'\. \&]+$/';
            $rules['company_division'] = 'required';
            $rules['company_district'] = 'required';
            $rules['company_thana'] = 'required';
        }
        $rules['user_agreement'] = 'required';
        $this->validate($request, $rules);

        try{

            if ($request->get('company_type') == 1) { // existing company
                $lastCompanyId = $request->get('company_id');
            } else if ($request->get('company_type') == 2) { // new company
                $companyData = CompanyInfo::where('company_name', $request->get('company_name'))->first();
                if (count($companyData) > 0) {
                    $currentTime = new Carbon;
                    $validateTime = new Carbon($companyData->updated_at . '+6 hours');
                    if ($currentTime < $validateTime) {
                        Session::flash('error', 'A company request exist as like your company name. If you valid user of the given company then please try again after 6 hours!');
                        return Redirect::back()->withInput();
                    } else {
                        $companyData->company_name = $request->get('company_name');
                        $companyData->division = $request->get('company_division');
                        $companyData->district = $request->get('company_district');
                        $companyData->thana = $request->get('company_thana');
                        $companyData->created_by = $result->id;
                        $companyData->created_at = Carbon::now();
                        $companyData->save();
                        $lastCompanyId = $companyData->id;

                        Users::where('user_sub_type', $companyData->id)
                            ->update(['user_sub_type' => '-1']);
                    }
                } else {
                    $companyData = new CompanyInfo();
                    $companyData->company_name = $request->get('company_name');
                    $companyData->division = $request->get('company_division');
                    $companyData->district = $request->get('company_district');
                    $companyData->thana = $request->get('company_thana');
                    $companyData->save();
                    $lastCompanyId = $companyData->id;
                }

            }

            $data = [
                'user_full_name' => $request->get('user_full_name'),
                'user_type' => $request->get('user_type'),
                'user_sub_type' => $lastCompanyId,
                'identity_type' => $request->get('identity_type'),
                'passport_no' => $request->get('passport_no'),
                'user_nid' => $request->get('user_nid'),
                'nationality' => $request->get('nationality'),
                'user_DOB' => CommonFunction::changeDateFormat($request->get('user_DOB'), true),
                'country' => $request->get('country'),
                'division' => $request->get('division'),
                'district' => $request->get('district'),
                'state' => $request->get('state'),
                'province' => $request->get('province'),
                'road_no' => $request->get('road_no'),
                'user_phone' => $request->get('user_phone'),
                'user_verification' => 'yes',
                'is_approved' => $approve_status,
                'user_agreement' => $request->get('user_agreement'),
            ];
           Users::where('id',$result->id)->update($data);
            $this->entryAccessLog();
           return redirect()->to('/dashboard');
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
            Session::flash('error', 'Something went wrong[SIGNUP10056]');
            return Redirect::back()->withInput();
        }
    }

    private function signupMail($request,$token)
    {
        try
        {
            $email = $request->get('user_email');
            $user_phone = $request->get('user_phone');
            $verify_link = 'signup/verification/' . ($token);
            $body_msg = "Thanks you for requesting to open an account in our system.<br/>
                              Click the following link to verify your email account.
                            <br/><a href='" . url($verify_link) . "'>Verify the e-mail address you have provided earlier</a>";


            $params = array([
                'emailYes' => '1',
                'emailTemplate' => 'Users::message',
                'emailBody' => $body_msg,
                'emailSubject' => 'Verify the e-mail address you have provided earlier',
                'emailHeader' => 'Verify the e-mail address you have provided earlier',
                'emailAdd' => $email,
                'mobileNo' => $user_phone,
                'smsYes' => '0',
                'smsBody' => '',
            ]);
            CommonFunction::sendMessageFromSystem($params);

            \Session::flash('success', 'Thanks for signing up! Please check your email and follow the instruction to complete the sign up process');
            return true;
        }
        catch (\Exception $e)
        {
            Session::flash('error', 'Sorry! Something went wrong, Please try again later.');
            return false;
        }
    }

    public function verification($confirmationCode)
    {
        $user = Users::where('user_hash', $confirmationCode)->first();
        if (!$user)
        {
            \Session::flash('error', 'Invalid Token! Please resend email verification link.');
            return redirect('login');
        }
        $currentTime = new Carbon;
        $validateTime = new Carbon($user->created_at . '+6 hours');

        if ($currentTime >= $validateTime)
        {
            Session::flash('error', 'Verification link is expired (validity period 6 hrs). Please sign up again!');
            return redirect('/login');
        }

        $user_type = $user->user_type;
        if ($user->user_verification != 'yes')
        {

            $company_list = ['' => 'Select Company '] + CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                                       ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
                                       ->select('id', DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info'))->where('is_approved', 1)->where('company_status',1)
                                       ->orderBy('company_name','ASC')->lists('company_info', 'id')->all();

            $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id')->all();
            $districts = AreaInfo::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_id');
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');

            return view('Signup::verification', compact('user_type', 'confirmationCode', 'districts','divisions','thana', 'company_list'));
        }
        else
        {
            \Session::flash('error', 'Invalid Token! Please sign up again.');
            return redirect('signup/resend-mail');
        }
    }

    function verificationStore($confirmationCode, Request $request, Users $usersmodel) {
        $TOKEN_NO = $confirmationCode;
        $user = Users::where('user_hash', $TOKEN_NO)->first();
        $email = $user->user_email;
        $user_password = str_random(10);
        if ($request->get('company_type') == 1) { // existing company
            $rules['company_id'] = 'required';
        } else { // new company
            $rules['company_name'] = 'required|regex:/^[a-zA-Z\'\. \&]+$/';
            $rules['division'] = 'required';
            $rules['district'] = 'required';
            $rules['thana'] = 'required';
        }
        if(!$user)
        {
            \Session::flash('error', 'Invalid token! Please sign up again to complete the process');
            return redirect('create');
        }
//		$this->validate($request, [
//			'user_agreement' => 'required',
//		]);
        $rules['user_agreement'] = 'required';
        $this->validate($request, $rules);

        $data = array(
            'details' => $request->get('details'),
            'user_agreement' => $request->get('user_agreement'),
            'password' => Hash::make($user_password),
            'user_verification' => 'yes',
            'user_first_login' => Carbon::now()
        );

        if ($request->get('company_type') == 1) { // existing company
            $data['user_sub_type'] = $request->get('company_id');
        } else if ($request->get('company_type') == 2) { // new company
            $companyData = CompanyInfo::where('company_name', $request->get('company_name'))->first();
            if (count($companyData) > 0) {
                $currentTime = new Carbon;
                $validateTime = new Carbon($companyData->updated_at . '+6 hours');
                if ($currentTime < $validateTime) {
                    Session::flash('error', 'A company request exist as like your company name. If you valid user of the given company then please try again after 6 hours!');
                    return Redirect::back()->withInput();
                } else {
                    $companyData->company_name = $request->get('company_name');
                    $companyData->division = $request->get('division');
                    $companyData->district = $request->get('district');
                    $companyData->thana = $request->get('thana');
                    $companyData->created_by = $user->id;
                    $companyData->created_at = Carbon::now();
                    $companyData->save();

                    $previousUsersBlacklisted = Users::where('user_sub_type', $companyData->id)
                        ->update(['user_sub_type' => '-1']);
                }
            } else {
                $companyData = new CompanyInfo();
                $companyData->company_name = $request->get('company_name');
                $companyData->division = $request->get('division');
                $companyData->district = $request->get('district');
                $companyData->thana = $request->get('thana');
                $companyData->save();
            }
            $data['user_sub_type'] = $companyData->id;
        }


        $usersmodel->chekced_verified($TOKEN_NO, $data);
        $body_msg = "<span style='color: #1164f3'>Your account password :<strong><code>" . $user_password . '</code></strong></span>';
        $body_msg .= "<br/>This is a sectret password generated by the system."
            . "But to ensure your own security and convenience, you should change the password after logging in.";
        $body_msg .= "<br/><br/><br/>Thanks, <br/> OSS Framework";



        $params = array([
            'emailYes' => '1',
            'emailTemplate' => 'Users::message',
            'emailBody' => $body_msg,
            'emailSubject' => 'Account Access Information',
            'emailHeader' => 'Verify the e-mail address you have provided earlier',
            'emailAdd' => $email,
            'mobileNo' => '01767957180',
            'smsYes' => '0',
            'smsBody' => '',
        ]);
        CommonFunction::sendMessageFromSystem($params);
        \Session::flash('success', 'Thanks for signing up! Please check your email for the account activation message.');
        return redirect('login');
    }

    public function resendMail(Request $request) {
        try
        {
            $email = Encryption::decodeId(Input::get('tmp'));
            $result = DB::table('users')->where('user_email', '=', $email)->first();
            $ACTIVE_STATUS = $result->user_status;
            $encrypted_token = Encryption::encode($result->user_hash);
            $verify_link = 'signup/verification/' . ($encrypted_token);


            if ($ACTIVE_STATUS == 'inactive')
            {
                $body_msg = "Verification link has given below.
                                <br/> <a href='" . url($verify_link) . "'>Click Here</a>";


                $params = array([
                    'emailYes' => '1',
                    'emailTemplate' => 'Users::message',
                    'emailBody' => $body_msg,
                    'emailSubject' => 'resend email subject-1',
                    'emailHeader' => 'Verify the e-mail address you have provided earlier',
                    'emailAdd' => $email,
                    'mobileNo' => '01767957180',
                    'smsYes' => '0',
                    'smsBody' => '',
                ]);
                CommonFunction::sendMessageFromSystem($params);

                \Session::flash("success", "An email has been re-sent to your address.<br/>
                                Please check the newest email and follow the instructions to complete the sign up process.<br/>
                                Thank you!<br/>");
            }
            elseif ($ACTIVE_STATUS == 'active') {

                $body_msg = "You are already active.";



                $params = array([
                    'emailYes' => '1',
                    'emailTemplate' => 'Users::message',
                    'emailBody' => $body_msg,
                    'emailSubject' => 'resend email subject-2',
                    'emailHeader' => 'Verify the e-mail address you have provided earlier',
                    'emailAdd' => $email,
                    'mobileNo' => '01767957180',
                    'smsYes' => '0',
                    'smsBody' => '',
                ]);
                CommonFunction::sendMessageFromSystem($params);

                \Session::flash('success', 'Please check your email for new update!');
            }
            $ecptEmail = Encryption::encodeId($email);
            return redirect('signup?tmp=' . $ecptEmail);
        }
        catch (\Exception $e)
        {
            Session::flash('error', 'Sorry! Something is Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function google_signUp(){

        $company_list = ['' => 'Select Company '] + CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
                ->select('id', DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info'))->where('is_approved', 1)->where('company_status',1)
                ->orderBy('company_name','ASC')->lists('company_info', 'id')->all();

        $user_types = ['' => 'Select One'] + UserTypes::orderBy('type_name')->where('is_registarable',1)->orderBy('type_name','ASC')->lists('type_name', 'id')->all();
        $countries = Countries::orderby('name')->lists('name', 'iso');
        $nationalities = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
        $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id')->all();
        $districts = AreaInfo::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_id');
        $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');


        return view("Signup::google_signup", compact("user_types", "nationalities", "thana","countries", "divisions", "districts","company_list"));
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
}
