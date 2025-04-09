<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guest()) {
            if ($request->ajax()) {
                return Response::make("unauthorized");
            } else {
                session('auth_redirect_url',url()->full());
                return redirect('login')->with('error', 'You are not authorized');
            }
        }else{
            if(session('tfa_verify')){
                return redirect('auth/tfa-verify?type=login');
            }
        }

        return $next($request);
    }
}
