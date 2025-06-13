<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin/setting/update', ['setting' => $setting]);
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
     * Save an uploaded file for the setting.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string',
            'image' => $this->general->fileRules('image')
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $this->general->getError($validator)]);
        }
        $key = $request->input('key');
        $setting = Setting::where('key', $key)->first();
        if ($setting) {
            $result = $this->general->uploadFile($request->file('image'), 'logo','','same');
            if ($result['status']) {
                if ($setting->value != $result['file_name']) {
                    $this->general->deleteFile($setting->value, 'logo');
                }
                $setting->value = $result['file_name'];
                $setting->save();
                $setting->clearCache();
            }
        }
        return response()->json($result);
    }



    /**
     * Send a test email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mailProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $this->general->getError($validator)]);
        }
        $subject = 'Email Test | ' . config('setting.app_name');
        $body = view('email/template', ['subject' => $subject, 'body' => 'Email Test'])->render();
        $this->general->sendEmailSMTP($request->input('email'), $subject, $body);
        return response()->json(['status' => 1, 'message' => 'Email Sent Successfully']);
    }
}
