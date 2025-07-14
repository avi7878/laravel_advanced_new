<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Class DeviceController
 *
 * Manages device-related operations in the admin panel, including displaying the device list
 * and logging out a device.
 *
 * @package App\Http\Controllers\Admin
 */
class DeviceController extends Controller
{
    /**
     * Displays the device index view.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin/device/index');
    }

    /**
     * Retrieves a list of devices for the admin panel.
     *
     * @param Request $request The HTTP request containing filter and search parameters.
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        return response()->json((new UserAuth())->listAdmin($request->all()));
    }

    /**
     * Logs out a device from the admin panel with a SweetAlert success message.
     *
     * @param Request $request The HTTP request containing the device ID.
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        (new UserAuth())->forceLogout($request->input('id'));

        return response()->json(['status' => 1, 'message' => 'Device Logout Successfully', 'next' => 'reload']);
    }
}
