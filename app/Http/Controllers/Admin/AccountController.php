<?php

namespace App\Http\Controllers\Admin;

use App\Models\Device;
use App\Models\UserActivity;
use App\Services\AccountService;
use App\Services\TfaService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('site/dashboard');
    }
    
    /**
     * Display the account update form.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function update(Request $request)
    {
        $model = auth()->user();

        return view('admin/account/update', compact('model'));
    }

    /**
     * Save updated account details.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        return response()->json((new AccountService())->save($request, auth()->user()));
    }

    /**
     * Display the change password form.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function passwordChange(Request $request)
    {
        $model = auth()->user();
        return view('admin.account.change_password', compact('model'));
    }

    /**
     * Process password change request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePasswordProcess(Request $request)
    {
        return response()->json((new AccountService())->changePassword($request, auth()->user()));
    }

    /**
     * Display the profile image update form.
     *
     * @return \Illuminate\View\View
     */
    public function image()
    {
        $model = auth()->user();
        return view('admin.account.component.image', compact('model'));
    }

    /**
     * Save updated profile image.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function imagesave(Request $request)
    {
        return response()->json((new AccountService())->saveImage($request, auth()->user()));
    }

    /**
     * Delete the current user's profile image.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage()
    {
        return response()->json((new AccountService())->deleteImage(auth()->user()));
    }


    public function tfa()
    {
        $model = auth()->user();
        return view('admin.account.tfa', compact('model'));
       // return view('admin/account/tfa', ['user' => auth()->user()]);
    }

    /**
     * Toggle TFA status.
     *
     * @return JsonResponse
     */
    public function tfaStatusChange()
    {
        $result = (new TfaService())->tfaStatusChange(auth()->user());
        return response()->json($result);
    }
    /**
     * Revoke all trusted devices for the current user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeAll()
    {
        return response()->json((new UserActivity())->listAdmin($request->all(), auth()->user()));
    }


    /**
     * Display the device management view.
     *
     * @return \Illuminate\View\View
     */
    public function device()
    {
        return view('admin.account.device');
    }

    /**
     * Retrieve the list of user's devices.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deviceList(Request $request)
    {
        return response()->json((new Device())->list($request->all(), auth()->id()));
    }

    /**
     * Log out the specified device.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deviceLogout(Request $request)
    {
        (new Device())->forceLogout($request->input('id'));

        return response()->json(['status' => 1, 'message' => 'Device Logout Successfully', 'next' => 'reload']);
    }

    /**
     * Display the user activity log view.
     *
     * @return \Illuminate\View\View
     */
    public function userActivity()
    {
        return view('admin.account.user_activity');
    }

    /**
     * Retrieve the list of user activity logs.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userActivityList(Request $request)
    {
        return response()->json((new UserActivity())->list($request->all(), auth()->id()));
        
    }

    
}
