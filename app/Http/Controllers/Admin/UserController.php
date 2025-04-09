<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 *
 * Handles user management functionalities in the admin panel.
 */
class UserController extends Controller
{
    /**
     * Display the user index view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin/user/index');
    }

    /**
     * Get a list of users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return response()->json((new User())->list($request->all()));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/user/create');
    }

    /**
     * Show the form for updating a specific user.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $model = User::find($request->id);
        $user = auth()->user();
        $permission = explode(',', $user->permission);
        // dd($model);
        if ($user->type == 1 && !in_array('admin/user/update', $permission)) {
            return redirect('admin/users')->with('error', 'No permission To Update User');
        }

        return view('admin/user/update', compact('permission', 'model'));
    }

    /**
     * Save or update user data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        return response()->json((new User())->store($request->all()));
    }

    /**
     * View a specific user's details along with logs and devices.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function view(Request $request)
    {
        $id = $request->input('id');
        $logData = Log::where('user_id', $id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        $deviceData = Device::where('user_id', $id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        $model = User::where('id', $id)->first();

        return view('admin/user/view', compact('model', 'logData', 'deviceData'));
    }

    /**
     * Delete a specific user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $model = User::find($request->input('id'));
        if (!$model) {
            return response()->json(['status' => 0, 'message' => 'No data found']);
        }
        $model->delete();
        return response()->json(['status' => 1, 'message' => 'User deleted successfully.', 'next' => 'table_refresh']);
    }

    /**
     * Change the status of a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()]);
        }

        $id = $request->input('id');
        $model = User::find($id);

        if (!$model) {
            return response()->json(['status' => 0, 'message' => 'User not found']);
        }

        $model->update(['status' => !$model->status]);  // Toggle the status
        return response()->json(['status' => 1, 'message' => 'User status updated successfully.', 'next' => 'refresh']);
    }

    /**
     * Revoke all devices for the currently authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeAll()
    {
        $model = Auth::user();
        $model->updateData(['ignore_tfa_device','']);
        return response()->json(['status' => 1, 'message' => 'Your devices revoked successfully.']);
    }
}
