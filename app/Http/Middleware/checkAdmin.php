<?php

namespace App\Http\Middleware;

use App\Http\Controllers\LoginController;
use App\Libraries\CommonFunction;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class checkAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $LgController = new LoginController;
        $user_type=Auth::user()->user_type;
        $user=explode("x",$user_type); // $user[0] array index stored the users level id

        // check first login to change password
        if(Auth::user()->first_login == 0 AND Auth::user()->social_login !=1 AND (in_array($user[0], [5,6]))){
            return redirect()
                ->intended('/users/profileinfo#tab_2')
                ->with('error', 'Please change the password.');
        }
        // check the user is approved
        if(Auth::user()->is_approved == 0){
            return redirect()
                ->intended('/dashboard')
                ->with('error', 'You are not approved user ! Please contact with system admin');
        }
        // check the user is delegate
        if(Auth::user()->delegate_to_user_id != 0){
                return redirect('users/delegate');
        }

        if (!$LgController->_checkSecurityProfile($request)) {
            Auth::logout();
            return redirect('/login')
                ->with('error', 'Security profile does not support in this time for operation.');
        }

//        if(CommonFunction::checkProfileInfo() == false){
//            Session::flash('checkProfile', '');
//            return redirect()
//                ->intended('/users/profileinfo');
//        }
        $uri = $request->segment(1);
        switch (true) {
            case ($uri == 'settings' and $user[0] == '1' or $user[0] == '13'):
                return $next($request); // allowed system admin users to access settings module
                break;
            case ($uri == 'dashboard' and ($user[0] == '1' or $user[0] == '2' or $user[0] == '3' or $user[0] == '4' or $user[0] == '5'  or $user[0] == '6')):
                return $next($request); // allowed dashboard  to access user module
                break;
            case ($uri == 'users' and ($user[0] == '1' || $user[0] == '2'  || $user[0] == '3'  or $user[0] == '4' or $user[0] == '5'  or $user[0] == '6'or $user[0] == '13')):
                return $next($request); // allowed users  to access agency module
                break;
            case ($uri == 'process-path' AND (in_array($user[0], [1]))):
                return $next($request); // allowed users  to access project clearance module
                break;
            case ($uri == 'space-allocation' AND (in_array($user[0], [1,2,4,5,7,8]))):
                return $next($request); // allowed users  to access project clearance module
                break;
            case ($uri == 'general-apps' AND (in_array($user[0], [1,2,4,5,7,8]))):
                return $next($request); // allowed users  to access project clearance module
                break;
            case ($uri == 'land-requisition' AND (in_array($user[0], [1,2,4,5,7,8]))):
                return $next($request); // allowed users  to access project clearance module
                break;
            case ($uri == 'import-permit' AND (in_array($user[0], [1,2,4,5,7,8]))):
                return $next($request); // allowed users  to access import permit module
                break;
            case ($uri == 'export-permit' AND (in_array($user[0], [1,2,4,5,7,8]))):
                return $next($request); // allowed users  to access export permit module
                break;
            case ($uri == 'visa-assistance' AND (in_array($user[0], [1,2,4,6,7,8]))):
                return $next($request); // allowed users  to access visa assistance module
                break;
            case ($uri == 'visa-recommend' AND (in_array($user[0], [1,2,4,5,7,8]))):
                return $next($request); // allowed users  to access visa recommendation module
                break;
            case ($uri == 'local-sales-permit' AND (in_array($user[0], [1,2,4,5,7,8]))):
                return $next($request); // allowed users  to access visa recommendation module
                break;
            case ($uri == 'loan-locator' AND (in_array($user[0], [1,2,4,5,7,8,13]))):
                return $next($request); // allowed users  to access visa recommendation module
                break;
            case ($uri == 'board-meting' AND (in_array($user[0], [1,2,4,5,7,8,13]))):
                return $next($request); // allowed users  to access visa recommendation module
                break;
            case ($uri == 'co-branded-card' AND (in_array($user[0], [1,2,4,5,7,8,13]))):
                return $next($request); // allowed users  to access visa recommendation module
                break;
            case ($uri == 'limit-renewal' AND (in_array($user[0], [1,2,4,5,7,8,13]))):
                return $next($request); // allowed users  to access visa recommendation module
                break;
            case ($uri == 'meeting-form' AND (in_array($user[0], [1,4,13]))):
                return $next($request); // allowed users  to access visa recommendation module
                break;
            case ($uri == 'exam'):
                $uri_2 = $request->segment(2);
                switch(true){
                    case ($uri_2=='question-bank' && $user[0] == '9'):
                        return $next($request);// allow for exam controller
                        break;
                    case ($uri_2=='schedule' && $user[0] == '9'):
                        return $next($request);// allow for exam controller
                        break;
                    case ($uri_2=='result' && $user[0] == '9'):
                        return $next($request);// allow for exam controller
                        break;

                    case ($uri_2=='exam-list' && $user[0] != '9'):
                        return $next($request);// allow all users without exam controller
                        break;

                    default:
                        Session::flash('error', 'Invalid URL ! error code(' . $uri.'/'.$uri_2. '-' . $user[0] . ')    ');
                        return redirect('dashboard');
                }
                break;
            default:
                Session::flash('error', 'Invalid URL ! error code('.$uri.'-'.$user[0].')    ');
                return redirect('dashboard');
                return $next($request);
        }
        return $next($request);
    }
}
