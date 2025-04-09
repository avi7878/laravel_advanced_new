<?php

namespace App\Services;

use App\Helpers\General;
use App\Models\UserActivity;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AccountService
{

    /**
     * Process user registration.
     *
     * @param array $postData
     * @return array
     */
    public function registerProcess(array $postData): array
    {
        $general = new General();
        if ($general->rateLimit('register')) {
            return ['status' => 0, 'message' => 'Too many attempts, please try again later.'];
        }
        if ($general->recaptchaFails()) {
            return ['status' => 0, 'message' => 'Please check recaptcha.'];
        }

        $validator = Validator::make($postData, [
            'first_name' => 'required|alpha|max:255',
            'last_name' => 'required|alpha|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => [
                'required',
                $general->passwordType()
            ],
            'password_confirm' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return ['status' => 0, 'message' => $validator->errors()->first()];
        }

        $result = $general->verifyEmail($postData['email']);
        if (!$result['status']) {
            return $result;
        }

        $ip = $general->getClientIp();

        $user = User::create([
            'first_name' => $postData['first_name'],
            'last_name' => $postData['last_name'],
            'email' => $postData['email'],
            'password' => (new AuthService())->encryptPassword($postData['password']),
            'country' => $general->getIpInfoCountry($ip),
            'status'      => 1,
            'timezone' => config('app.timezone'),
            'data' => json_encode(['registered_ip' => $ip])
        ]);

        (new UserActivity())->add($user->id, 3);

        if (config('setting.user_email_verify')) {
            (new \App\Services\TfaService())->sendOTP($user, 'Verify_email');
            $token = base64_encode($user->email);
            return ['status' => 1, 'message' => 'Thank you for registration, Please verify your email.', 'next' => 'redirect', 'url' => 'auth/verify?type=email&token=' . $token];
        } else {
            Auth::guard()->login($user);
            (new Device())->login($user->id, 0);
            (new UserActivity())->add($user->id, 1);
        }
        return ['status' => 1, 'message' => 'Thank you for registration', 'next' => 'redirect', 'url' => config('setting.login_redirect_url')];
    }

    /**
     * Save user account details.
     *
     * @param Request $request
     * @param User $user
     * @return array
     */
    public function save(Request $request, User $user): array
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'message' => $validator->errors()->first()
            ];
        }
        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email')
        ]);

        return ['status' => 1, 'message' => 'Account Updated Successfully', 'next' => 'reload'];
    }

    /**
     * Change user password.
     *
     * @param Request $request
     * @param User $user
     * @return array
     */
    public function changePassword(Request $request, User $user): array
    {
        $general = new General();
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => [
                'required',
                $general->passwordType()
            ],
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return ['status' => 0, 'message' => $validator->errors()->first()];
        }

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return ['status' => 0, 'message' => 'Old password does not match!'];
        }

        $user->update(['password' => bcrypt($request->input('confirm_password'))]);

        return ['status' => 1, 'message' => 'Password Updated Successfully', 'next' => 'refresh'];
    }

    /**
     * Save user profile image.
     *
     * @param Request $request
     * @param User $user
     * @return array
     */
    public function saveImage(Request $request, User $user): array
    {
        $general = new General();
        $validator = Validator::make($request->all(), [
            'image' => 'required|' . $general->fileRules('image'),
        ]);
        if ($validator->fails()) {
            return ['status' => 0, 'message' => $validator->errors()->first()];
        }
        $uploadResult = $general->uploadFile($request->file('image'), 'profile');
        if (!$uploadResult['status']) {
            return $uploadResult;
        }
        if ($uploadResult['file_name']) {
            if ($user->image) {
                $general->deleteFile($user->image, 'profile');
            }
            $user->update(['image' => $uploadResult['file_name']]);
            return ['status' => 1, 'message' => 'Account Updated Successfully', 'next' => 'hide_modal,reload'];
        }
        return ['status' => 0, 'message' => 'Upload Unsuccessful'];
    }

    /**
     * Delete user profile image.
     *
     * @param User $user
     * @return array
     */
    public function deleteImage(User $user): array
    {
        $general = new General();
        if (!$user->image) {
            return ['status' => 0, 'message' => 'Image not found'];
        }
        $general->deleteFile($user->image, 'profile');
        $user->update(['image' => null]);
        return ['status' => 1, 'message' => 'Image Deleted Successfully', 'next' => 'reload'];
    }

    /**
     * Revoke all TFA devices for the user.
     *
     * @param User $user
     * @return array
     */
    public function revokeAllTFADevices(User $user): array
    {
        $user->updateData(['ignore_tfa_device' => '']);
        return ['status' => 1, 'message' => 'Your Devices Revoked Successfully.', 'next' => 'refresh'];
    }
}
