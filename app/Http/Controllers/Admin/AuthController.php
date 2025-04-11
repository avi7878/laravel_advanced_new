<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;   
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\AuthService;
use App\Services\TfaService;

/**
 * Class AuthController
 *
 * Manages user authentication, including login, TFA (Two-Factor Authentication), OTP-based login, social login, and password recovery.
 */
class AuthController extends Controller
{
    /**
     * Display the login view or redirect if the user is authenticated via cookie.
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function login(Request $request)
    {
        $userToken = $request->cookie(config('setting.app_uid') . '_user_token');
        if ($userToken && !$this->general->rateLimit('remember_login')) {
            $result = (new AuthService())->loginByAuthToken($userToken);
            if ($result['status']) {
                return redirect($this->general->authRedirectUrl('admin/dashboard'));
            }
        }
        return view('admin/auth/login');
    }

    /**
     * Process login with validation, rate limiting, and authentication.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function loginProcess(Request $request): RedirectResponse
    {
       
        $result = (new AuthService())->loginProcess($request->only(['email', 'password']), 0);
        if (!$result['status']) {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }
        return redirect($this->general->authRedirectUrl('admin/dashboard'))->with('success', $result['message']);
    }
    

    /**
     * Log out the authenticated user and clear session data.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        if (Auth::check()) {
            Auth::logout();
            (new \App\Models\Device())->logout();
        }
        return redirect('admin/auth/login')->withCookie(cookie()->forget(config('setting.app_uid') . '_user_token'));
    }
    
    /**
     * Display the password forgot view.
     *
     * @return \Illuminate\View\View
     */
    public function passwordForgot()
    {
        return view('admin.auth.password_forgot');
    }
    
    /**
     * Process password forgot request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordForgotProcess(Request $request)
    {
       
        return (new AuthService())->passwordForgotProcess($request->only(['email','otp','password','password_confirm','step']),0);
    }
    // ----------------- Two-Factor Authentication (TFA) Methods -------------------

    /**
     * Show the TFA verification page.
     *
     * @return View
     */
    public function verify(Request $request)
    {
      
        $type=$request->get('type');
       
        $code=$request->get('code','');
        return view('admin/auth/verify',compact('type','code'));
    }

    /** 
     * Process TFA OTP verification.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function verifyProcess(Request $request)
    {
        return response()->json((new TfaService())->verifyProcess($request->only(['otp','type','code','skip_tfa'])));
    }

    /**
     * resend otp.
     *
     * @return \Illuminate\View\View
     */
    public function resendOTP(Request $request)
    {
        return response()->json((new TfaService())->resendOTP($request->only(['type','code'])));
    }
    
    // ------------------- Two-Factor Authentication (TFA) Methods -------------------


}
