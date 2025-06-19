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

    function checkTotp($secret, $code, $discrepancy = 1)
    {
        $currentTimeSlice = floor(time() / 30);

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $validCode = $this->getTotpCode($secret, $currentTimeSlice + $i);
            if ($validCode === $code) {
                return ['status' => 1, 'message' => 'Success'];
            }
        }

        return ['status' => 0, 'message' => 'Invalid OTP'];
    }

    /**
     * Send login OTP to the user.
     *
     * @param User $user The user to send the OTP to.
     * @param string $type The type of OTP (default is 'tfa').
     * @return array The result of the OTP send operation.
     */
    public function sendOTP(User $user, $type = 'otp'): array
    {
        if ($type == 'new_email') {
            $message = 'verity your new email/phone';
        } else if ($type == 'verify_account') {
            $message = 'verify your account';
        } else if ($type == 'forgot_password') {
            $message = 'reset your password';
        } else {
            $message = 'login';
        }

        $otp = $this->generateOtp();
        $user->otp = $otp . '_' . time();
        $user->otp_failed = 0;
        $user->save();
        (new General())->sendEmail($user->email, 'otp', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'otp' => $otp,
            'message' => $message,
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
        return (new TfaService())->sendOTP($user, $postData['type']);
    }

    /**
     * Verify the OTP process.
     *
     * @param array $postData The post data containing the OTP and other details.
     * @return array The result of the OTP verification process.
     */
    public function verifyProcess(array $postData, $type = 1): array
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

        if ($user->otp_failed >= config('setting.login_max_attempt')) {
            return ['status' => 0, 'message' => 'Too many attempts, please try again later.'];
        }

        $result = $this->checkOtp($postData['otp'], $user->otp);
        $resultTotp = $this->checkTotp($postData['otp'], $user->otp);

        if (!$result['status'] && !$resultTotp['status']) {
            $user->otp_failed = $user->otp_failed + 1;
            $user->save();
            return ['status' => 0, 'message' => 'Invalid OTP.'];
        }



        if ($postData['type'] == 'tfa') {
            if (@$postData['skip_tfa']) {
                $ignoredDevices = explode(',', $user->ignore_tfa_device);
                $token = $_COOKIE[config('setting.app_uid') . '_token'] ?? null;
                if ($token && !in_array($token, $ignoredDevices)) {
                    $ignoredDevices[] = $token;
                    $ignoredDevices = array_filter($ignoredDevices);
                    $ignoredDevices = array_unique($ignoredDevices);
                    $user->ignore_tfa_device = implode(',', $ignoredDevices);
                }
            }
            Session::forget('verify_tfa');
        } else if ($postData['type'] == 'new_email') {
            $user->email = $user->new_email;
            $user->phone = $user->new_phone;
        }
        $user->otp = '';
        $user->otp_failed = 0;
        $user->save();
        $redirectUrl = $general->authRedirectUrl($type ? config('setting.login_redirect_url') : config('setting.admin_login_redirect_url'));
        return ['status' => 1, 'message' => 'OTP verified successfully.', 'next' => 'redirect', 'url' => $redirectUrl];
    }

    public function generateTotpQrcode($userName)
    {
        // $secret = 123456789;
        $secret = $this->generateTotpSecretKey();
        $issuer = config('app.name');
        $qrCodeData = "otpauth://totp/$issuer:$userName?secret=$secret&issuer=$issuer";
        $qrGen = new \App\Helpers\QrGenerator();
        $qrCode = $qrGen->render_svg('qr', $qrCodeData, []);
        return [
            'qrCode' => $qrCode,
            'secretKey' => $secret,
        ];
    }

    function generateTotpSecretKey($length = 16)
    {
        $validChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; // Base32 alphabet
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $validChars[random_int(0, 31)];
        }
        return $secret;
    }



    function getTotpCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }
        $secretKey = $this->base32Decode($secret);
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $truncatedHash = substr($hash, $offset, 4);
        $code = unpack('N', $truncatedHash)[1] & 0x7FFFFFFF;
        $code = $code % 1000000;
        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }

    function base32Decode($b32)
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; // Base32 alphabet
        $b32 = strtoupper($b32);
        $b32 = preg_replace('/[^A-Z2-7]/', '', $b32);
        $binary = '';
        foreach (str_split($b32) as $char) {
            $binary .= str_pad(base_convert(strpos($alphabet, $char), 10, 2), 5, '0', STR_PAD_LEFT);
        }
        $bytes = [];
        foreach (str_split($binary, 8) as $byte) {
            if (strlen($byte) === 8) {
                $bytes[] = chr(bindec($byte));
            }
        }
        return implode('', $bytes);
    }

    function verifyTotp($secret, $code, $discrepancy = 1)
    {
        $currentTimeSlice = floor(time() / 30);

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $validCode = $this->getTotpCode($secret, $currentTimeSlice + $i);
            if ($validCode === $code) {
                return true;
            }
        }

        return false;
    }
}