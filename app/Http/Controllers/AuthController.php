<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;
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
                return redirect($this->general->authRedirectUrl('dashboard'));
            }
        }
        return view('auth/login');
    }

    /**
     * Process login with validation, rate limiting, and authentication.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function loginProcess(Request $request)
    {
        return response()->json((new AuthService())->loginProcess($request->only(['email', 'password','remember'])));
    }

    /**
     * Process the OTP login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginOtpProcess(Request $request)
    {
        return (new AuthService())->loginOtpProcess($request->only(['email', 'otp', 'step']));
    }


    /**
     * Log out the authenticated user and clear session data.
     *
     * @return RedirectResponse
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
            (new \App\Models\Device())->logout();
        }
        return redirect('login')->withCookie(cookie()->forget(config('setting.app_uid') . '_user_token'));
    }



   
    // ----------------- Two-Factor Authentication (TFA) Methods -------------------
    /**
     * Show the TFA verification page.
     *
     * @return View
     */
    public function verify(Request $request)
    {
        $type = $request->get('type');
        $code = $request->get('code', '');
        return view('auth/verify', compact('type', 'code'));
    }

    /** 
     * Process TFA OTP verification.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function verifyProcess(Request $request)
    {
        return response()->json((new TfaService())->verifyProcess($request->only(['otp', 'skip_tfa', 'type'])));
    }

    /**
     * resend otp.
     *
     * @return \Illuminate\View\View
     */
    public function resendOTP(Request $request)
    {
        return response()->json((new TfaService())->resendOTP($request->only(['type', 'code'])));
    }

    // ----------------- Two-Factor Authentication (TFA) Methods END-------------------



    // Social Login methods

    /**
     * Redirect to social login provider.
     *
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function socialLogin(Request $request)
    {
        return \Laravel\Socialite\Facades\Socialite::driver($request->route('type'))->redirect();
    }

    /**
     * Handle social login callback and process user data.
     *
     * @param string $provider
     * @return RedirectResponse
     */
    public function socialLoginCallback(string $type): RedirectResponse
    {
        $socialUser = \Laravel\Socialite\Facades\Socialite::driver($type)->stateless()->user();
        $user = User::where(function ($query) use ($socialUser) {
            $query->where('email', $socialUser->email)
                ->orWhere("phone", $socialUser->phone);
        })->where('type', 1)->first();
        $user = (new User())->where('email', $socialUser->email)->first();
        Auth::login($user);
        return redirect($this->general->authRedirectUrl(config('setting.login_redirect_url')));
    }
}
