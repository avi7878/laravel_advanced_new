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

    /**
     * resend otp.
     *
     * @return \Illuminate\View\View
     */
    public function resendOtp(Request $request)
    {
        if ($this->general->rateLimit('resend_otp',5)) {
            return ['status' => 0, 'message' => 'Too many attempts, please try again later.'];
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return ['status'=>0,'message' => 'Email Not Valid'];
        }
        if ($user->status == 0) {
            return ['status' => 0, 'message' => 'Your Account is blocked'];
        }

        return (new TfaService())->sendOTP($user->email); 
    }
    
    /**
     * Display password reset view.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function passwordReset(Request $request)
    {   
        $postData=$request->only(['code']);
        if (!(new AuthService())->passwordResetLinkIsValid($postData,0)) {
            return redirect('login')->withErrors('error' , 'Link is invalid or expired');
        }

        return view('admin/auth/password_reset', ['code' => $request->input('code')]);
    }
    
    /**
     * Process password reset request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordResetProcess(Request $request)
    {
        $result=(new AuthService())->passwordResetProcess($request->only(['password','code','password_confirmation']));
        if (!$result['status']) {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }
        return redirect('admin/auth/login')->with('success', $result['message']);
    }
    
    // ------------------- Two-Factor Authentication (TFA) Methods -------------------

    /**
     * Show TFA settings page.
     *
     * @return View
     */
    public function tfa()
    {
        return view('admin/auth/tfa', ['user' => auth()->user()]);
    }

    /**
     * Toggle TFA status.
     *
     * @return JsonResponse
     */
    public function tfaStatusChange()
    {
        return response()->json((new TfaService())->tfaStatusChange(auth()->user()));
    }

    /**
     * Show the TFA verification page.
     *
     * @return View
     */
    public function tfaVerify(): View
    {
        return view('admin/auth/tfa_verify');
    }

    /**
     * Send OTP for TFA.
     *
     * @return JsonResponse
     */
    public function tfaSendOTP(): JsonResponse
    {
        return response()->json((new TfaService())->sendOTP(auth()->user()));
    }
    
    
    public function tfaVerifyProcess(Request $request): RedirectResponse
    {
        $result = (new TfaService())->tfaVerify($request, auth()->user());
        if (!$result['status']) {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }
        return redirect()->to('admin/dashboard')->with('success', 'TFA verified successfully');
    }
    
    public function otpLoginVerify(Request $request)
    {
        $code = $request->input('code');
        return view('admin/auth/otp_login_verify', compact('code'));
    }

}
