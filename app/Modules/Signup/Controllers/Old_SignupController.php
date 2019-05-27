<?php

namespace App\Modules\Signup\Controllers;

//use App\Http\Requests;
//use App\Http\Controllers\Controller;
//
//use Illuminate\Http\Request;







use App\Http\Requests;
use App\Http\Controllers\Controller;

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
use yajra\Datatables\Datatables;
use Validator;

class SignupControlle1r extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
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


		if ($result)
		{
			if ($result->user_verification == 'no')
			{
				\Session::flash('verifyNo',"You have previously received a sign up request with this email address, which did not get verified yet.
											If you did not get any link to verify your email address, please click the 'Resend Email' button and
											follow the given instructions to complete your sign up process.");
				return redirect('signup?tmp=' . Encryption::encodeId($request->get('user_email')));
			}
			else if ($result->user_verification == 'yes')
			{
				\Session::flash('verifyYes', 'Sorry, You have already registered with this email address. Did you forget your password?');
				return redirect('signup');
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

	private function signupMail($request,$token)
	{
		try
		{
			$email = $request->get('user_email');
			$verify_link = 'signup/verification/' . ($token);
			$body_msg = "Thanks you for requesting to open an account in our system.<br/>
                              Click the following link to verify your email account.
                            <br/><a href='" . url($verify_link) . "'>Verify the e-mail address you have provided earlier</a>";
			$email_data = array(
				'header' => 'Please verify your email address',
				'param' => $body_msg
			);

			\Mail::send('Users::message', $email_data, function ($message) use ($email) {
				$message->from('no-reply@OCPL.gov.bd', 'Hi-Tech Park Authority')
					->to($email)
					->subject('Verify the e-mail address you have provided earlier');
			});

			\Session::flash('success', 'Thanks for signing up! Please check your email and follow the instruction to complete the sign up process');
			return true;
		}
		catch (\Exception $e)
		{
			Session::flash('error', 'Sorry! Something went wrong, Please try again later.');
			return false;
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
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
			$districts = ['' => 'Select one'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
			return view('Signup::verification', compact('user_type', 'confirmationCode', 'districts'));
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
		if(!$user)
		{
			\Session::flash('error', 'Invalid token! Please sign up again to complete the process');
			return redirect('create');
		}
		$this->validate($request, [
			'user_agreement' => 'required',
		]);
		$data = array(
			'details' => $request->get('details'),
			'user_agreement' => $request->get('user_agreement'),
			'password' => Hash::make($user_password),
			'user_verification' => 'yes',
			'user_first_login' => Carbon::now()
		);

		$usersmodel->chekced_verified($TOKEN_NO, $data);
		$body_msg = "Your account password :<strong><code>" . $user_password . '</code></strong>';
		$body_msg .= "<br/>This is a sectret password generated by the system."
			. "But to ensure your own security and convenience, you should change the password after logging in.";
		$body_msg .= "<br/><br/><br/>Thanks, <br/> Hi-Tech Park System Authority";

		$email_data = array(
			'header' => 'Account Access Information',
			'param' => $body_msg
		);
		\Mail::send('Users::message', $email_data, function ($message) use ($email) {
			$message->from('no-reply@OCPL.gov.bd', 'Business Automation-10')
				->to($email)
				->subject('Demo subject - '.time());
		});
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
				$data = array(
					'header' => 'Please verify your email address',
					'param' => $body_msg
				);
				\Mail::send('signup::message', $data, function($message) use ($email) {
					$message->from($this->email_sender_add, 'OCPL. Syste-1')
						->to($email)
						->subject('resend email subject-1');
				});
				\Session::flash("success", "An email has been re-sent to your address.<br/>
                                Please check the newest email and follow the instructions to complete the sign up process.<br/>
                                Thank you!<br/>");
			}
			elseif ($ACTIVE_STATUS == 'active') {

				$body_msg = "You are already active.";
				$data = array(
					'header' => 'Account Activation Information',
					'param' => $body_msg
				);
				\Mail::send('users::message', $data, function($message) use ($email) {
					$message->from($this->email_sender_add, 'OCPL. Syste-2')
						->to($email)
						->subject('resend email subject-2');
				});
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
}
