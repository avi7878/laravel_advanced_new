<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Fixed namespace for the base Controller
use Illuminate\Http\Request;
use App\Models\UserActivity;

/**
 * Class AccountActivityController
 * 
 * This controller handles the log management in the admin panel.
 */
class AccountActivityController extends Controller
{
    /**
     * Display the log index view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.account_activity.index'); // Use dot notation for view paths
    }

    /**
     * List logs for admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $logs = (new UserActivity())->listAdmin($request->all());
        return response()->json($logs);
    }
}
