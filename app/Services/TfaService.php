<?php

namespace App\Services;

use App\Helpers\General;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class TfaService
{
    /**
     * Generate a random OTP.
     *
     * @return int
     */
    public function generateOtp(): int
    {
        return random_int(100000, 999999);
    }

    /**
     * Encrypts the given email using base64 encoding.
     *
     * @param string $email The email to be encrypted.
     * @return string The encrypted token.
     */
    public function encryptCode(string $email): string
    {
        return base64_encode($email);
    }

    /**
     * Decrypts the given email using base64 decoding.
     *
     * @param string $email The encrypted token to be decrypted.
     * @return string The decrypted email.
     */
    public function decryptCode(string $email): string
    {
        return base64_decode($email);
    }

    /**
     * Check if the provided OTP matches the user's login OTP.
     *
     * @param int $otp The OTP provided by the user.
     * @param string|null $loginOtp The OTP stored in the user's login session.
     * @return array The result of the OTP check.
     */
    public function checkOtp(int $otp, ?string $loginOtp): array
    {
        if (!$loginOtp) {
            return ['status' => 0, 'message' => 'OTP is invalid'];
        }

        $loginOtpParts = explode('_', $loginOtp);
        $time = $loginOtpParts[1] ?? 0;
        if ($time < (time() - config('setting.token_expire_time'))) {
            return ['status' => 0, 'message' => 'OTP is expired'];
        }

        if ($otp != ($loginOtpParts[0] ?? null)) {
            return ['status' => 0, 'message' => 'OTP is invalid'];
        }

        return ['status' => 1, 'message' => 'Success'];
    }

    /**
     * Send login OTP to the user.
     *
     * @param User $user The user to send the OTP to.
     * @param string $type The type of OTP (default is 'tfa').
     * @return array The result of the OTP send operation.
     */
    public function sendOTP(User $user, $template = 'otp'): array
    {
        $otp = $this->generateOtp();
        $user->updateData(['otp' => $otp . '_' . time(), 'otp_failed' => 0]);
        (new General())->sendEmail($user->email, $template, [
            'name' => $user->first_name . ' ' . $user->last_name,
            'otp' => $otp,
        ]);
        return ['status' => 1, 'message' => 'OTP sent successfully'];
    }

    /**
     * Resend OTP to the user.
     *
     * @param array $postData The post data containing the type and code.
     * @return \Illuminate\Http\JsonResponse The response of the resend OTP operation.
     */
    public function resendOTP(array $postData)
    {
        $general = new General();
        if ($general->rateLimit('resend_otp', 5)) {
            return response()->json(['status' => 0, 'message' => 'Too many attempts, please try again later.']);
        }

        if ($postData['type'] == 'tfa') {
            $user = auth()->user();
        } else {
            $email = $this->decryptCode($postData['code']);
            $user = User::where('email', $email)->first();
        }

        if ($user->status == 0) {
            return response()->json(['status' => 0, 'message' => 'Your Account is blocked']);
        }
        return (new TfaService())->sendOTP($user, 'otp');
    }

    /**
     * Verify the OTP process.
     *
     * @param array $postData The post data containing the OTP and other details.
     * @return array The result of the OTP verification process.
     */
    public function verifyProcess(array $postData): array
    {
        $general = new General();
        if ($general->rateLimit('verify_tfa')) {
            return ['status' => 0, 'message' => 'Too many attempts, please try again later.'];
        }

        $validator = Validator::make($postData, [
            'otp' => 'required|digits:6',
        ]);
        if ($validator->fails()) {
            return ['status' => 0, 'message' => $validator->errors()->first()];
        }
        
        $user = auth()->user();

        if ($user->status == 0) {
            return ['status' => 0, 'message' => 'Your Account is blocked'];
        }
        $userData = $user->getData();
        if ($userData->otp_failed >= config('setting.login_max_attempt')) {
            return ['status' => 0, 'message' => 'Too many attempts, please try again later.'];
        }
        $result = $this->checkOtp($postData['otp'], $userData->otp);
        if (!$result['status']) {
            $user->updateData(['otp_failed' => $userData->otp_failed + 1]);
            return $result;
        }

        if (@$postData['skip_tfa']) {
            $ignoredDevices = explode(',', $userData->ignore_tfa_device);
            $token = $_COOKIE[config('setting.app_uid') . '_token'] ?? null;
            if ($token && !in_array($token, $ignoredDevices)) {
                $ignoredDevices[] = $token;
                $ignoredDevices = array_filter($ignoredDevices);
                $ignoredDevices = array_unique($ignoredDevices);
                $user->setData(['ignore_tfa_device' => implode(',', $ignoredDevices)]);
            }
        }
        Session::forget('verify_tfa');
        
        $user->setData(['otp' => '']);
        $user->save();
        $redirectUrl = config('setting.login_redirect_url');
        return ['status' => 1, 'message' => 'OTP verified successfully.', 'next' => 'redirect', 'url' => $redirectUrl];
    }


    
}
