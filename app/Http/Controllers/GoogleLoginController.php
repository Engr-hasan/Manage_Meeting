<?php
namespace App\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\Session;
use Socialite;
use DB;


class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
		try{
			$user = Socialite::driver('google')->user();

			$data = [
			//	'user_type' => '12x432',
				'user_nid' => $user->getId(),
				'user_email' => $user->getEmail(),
				'user_full_name' => $user->getName(),
				'user_pic' => $user->avatar_original,
				'password' => Hash::make('Google'),
				'is_approved' => 1,
				'first_login' => 1,
				'social_login' => 1,
				'security_profile_id' => 1
			];
			$getAlreadyUser = Users::where('user_email', $user->getEmail())->first();

			if($getAlreadyUser==''){
				$users = Users::firstOrCreate($data);
				Auth::loginUsingId($users->id);
				$users->login_token = Encryption::encode(Session::getId());
				$users->save();

				return redirect()->to('/google_signUp');
			} else {
				if ($getAlreadyUser->user_status == 'active'){
					Auth::loginUsingId($getAlreadyUser->id);
					$getAlreadyUser->login_token = Encryption::encode(Session::getId());
					$getAlreadyUser->save();
                    $this->entryAccessLog();
					return redirect()->to('/dashboard');
				}
				else{
					Session::flash('error',"User not activated!");
					return redirect()->to('/login');
				}
			}
			

		}
        catch(\Exception $e)
        {
            dd($e->getLine());
            Auth::logout();
			return redirect()->to('/login');
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
}