<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\General;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class SettingController
 *
 * Handles the management of application settings.
 *
 * @package App\Http\Controllers\Admin
 */
class SettingController extends Controller
{
    /**
     * Display the settings update form.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function update(Request $request)
    {
        $setting = $this->general->getAllSettings();
        $timezonelist = (new General())->getTimezooneList();

        return view('admin/setting/update', ['setting' => $setting, 'timezonelist' => $timezonelist]);
    }

    /**
     * Save application settings.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        return response()->json((new Setting())->store($request->all()));
    }

    /**
     * Save an uploaded file for the setting.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string',
            'image' => 'file|mimes:png|max:1024'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $this->general->getError($validator)]);
        }
        $key = $request->input('key');
        $fileName = $this->general->uploadFile($request->file('image'), 'setting', $key);
        if ($fileName) {
            $setting = Setting::where('key', 'app_' . $key)->first();
            if ($setting) {
                if ($setting->value) {
                    $this->general->deleteFile($setting->value, 'setting');
                }
                $setting->value = $fileName;
                $setting->save();
                $setting->clearCache();
            }
        }
        return response()->json(['status' => 1, 'message' => 'Data saved successfully']);
    }

    /**
     * Clear the settings cache.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cacheClear(Request $request)
    {
        (new Setting())->clearCache();
        return response()->json(['status' => 1, 'message' => 'Setting cache cleared']);
    }

    /**
     * Save SMTP settings.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function smtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'host' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'encryption' => 'required|string',
            'port' => 'required|integer',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $this->general->getError($validator)]);
        }

        $setting = $request->only([
            'host',
            'username',
            'password',
            'encryption',
            'port',
            'mail_from_address',
            'mail_from_name',
        ]);

        (new Setting())->updateAll($setting);
        return response()->json(['status' => 1, 'message' => 'Data saved successfully']);
    }

    /**
     * Save CAPTCHA settings.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function captcha(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'google_recaptcha' => 'required|string',
            'google_recaptcha_secret_key' => 'required|string',
            'google_recaptcha_public_key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $this->general->getError($validator)]);
        }

        $setting = $request->only([
            'google_recaptcha',
            'google_recaptcha_secret_key',
            'google_recaptcha_public_key',
        ]);

        (new Setting())->updateAll($setting);
        return response()->json(['status' => 1, 'message' => 'Data saved successfully']);
    }

    /**
     * Save social login settings.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function social(Request $request)
    {
        $setting = $request->only([
            'google_client_id',
            'google_client_secret',
            'google_login',
        ]);

        (new Setting())->updateAll($setting);
        return response()->json(['status' => 1, 'message' => 'Data saved successfully']);
    }

    /**
     * Save header and footer content settings.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function content(Request $request)
    {
        $setting = $request->only([
            'header_content',
            'footer_content',
        ]);

        (new Setting())->updateAll($setting);
        return response()->json(['status' => 1, 'message' => 'Data saved successfully']);
    }

    /**
     * Save payment settings.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payment(Request $request)
    {
        $setting = $request->only([
            'stripe_enable',
            'stripe_secret_key',
            'stripe_public_key',
        ]);

        (new Setting())->updateAll($setting);
        return response()->json(['status' => 1, 'message' => 'Data saved successfully']);
    }

    /**
     * Send a test email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mailprocess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $this->general->getError($validator)]);
        }
        $subject = 'Test Admin';
        $this->general->sendMail($request->input('email'), 'email | ' . config('setting.app_name'), view('email/admin/template', compact('subject'))->render());
        return response()->json(['status' => 1, 'message' => 'Email Sent Successfully']);
    }
}