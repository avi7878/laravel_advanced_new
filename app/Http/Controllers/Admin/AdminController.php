<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Device;
use App\Models\UserActivity;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin/admin/index');
    }

    /**
     * List the admins based on the request data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return response()->json((new User())->listAdmin($request->all()));
    }

    /**
     * Show the form for creating a new admin.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = new User();
        return view('admin/admin/create', compact('model'));
    }

    /**
     * Show the form for updating an existing admin.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update(Request $request)
    {
        $model = User::find($request->input('id'));
        if (!$model) {
            return redirect('admin/admin')->withError('error', 'No data found');
        }
        return view('admin/admin/update', compact('model'));
    }

    /**
     * Save a new admin or update an existing admin.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        return response()->json((new User())->storeAdmin($request->all()));
    }

    /**
     * Delete an existing admin.
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
        return response()->json(['status' => 1, 'message' => 'Data deleted successfully.', 'next' => 'table_refresh']);
    }

    /**
     * View details of a specific admin.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function view(Request $request)
    {
        $id = $request->input('id');
        $logData = UserActivity::where('user_id', $id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        $deviceData = Device::where('user_id', $id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        $model = User::where('id', $id)->first();

        return view('admin/admin/view', compact('model', 'logData', 'deviceData'));
    }
}
