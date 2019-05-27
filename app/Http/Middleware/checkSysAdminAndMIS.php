<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class checkSysAdminAndMIS
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
        if (Auth::user()->user_type != '1x101' and Auth::user()->user_type != '15x151') {

            Session::flash('error', 'Invalid URL ! This incident will be reported.');
            return redirect('dashboard');
        }

        return $next($request);
    }
}
