<?php
namespace App\Http\Controllers;

use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\Session;
use Socialite;


class FacebookLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {

        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        dd(1);
        try{
            $user = Socialite::driver('facebook')->user();
            //dd($user);
            $data = [
                'user_type' => '4x404',
                'user_nid' => $user->getId(),
                'user_email' => $user->getEmail(),
                'user_full_name' => $user->getName(),
                'user_pic' => $user->avatar_original,
                'password' => Hash::make('Google'),
                'is_approved' => 1,
                'social_login' => 1,
                'security_profile_id' => 1
            ];
            $getAlreadyUser = Users::where('user_email', $user->getEmail())->first();
            if(!$getAlreadyUser){
                $users = Users::firstOrCreate($data);
                Auth::loginUsingId($users->id);
                $users->login_token = Encryption::encode(Session::getId());
                $users->save();
                return redirect()->to('/dashboard');
            } else {
                //		    dd($getAlreadyUser->id);
                if ($getAlreadyUser->user_status == 'active'){
                    Auth::loginUsingId($getAlreadyUser->id);
                    $getAlreadyUser->login_token = Encryption::encode(Session::getId());
                    $getAlreadyUser->save();
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
            Auth::logout();
            return redirect()->to('/login');
        }
    }
}