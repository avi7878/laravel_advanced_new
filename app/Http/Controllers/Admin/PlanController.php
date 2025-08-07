<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class PlanController
 * @package App\Http\Controllers\Admin
 *
 * Handles plan management functionalities in the admin panel.
 */
class PlanController extends Controller
{ 
    /**
     * Display the plan index view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $sessionUser = auth()->user();
        if($sessionUser->hasPermission('admin/plan')){
            return view('admin/plan/index');
        }else{
          return redirect('admin/dashboard')->with('error', 'You are not authorized');
        }
    }

    /**
     * Get a list of plans.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return response()->json((new Plan())->list($request->all()));
    }

    /**
     * Show the form for creating a new plan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/plan/create');
    }

    /**
     * Show the form for updating a specific plan.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $model = Plan::find($request->id);
        $plan = auth()->user();
        $permission = explode(',', $plan->permission);
        if ($plan->type == 1 && !in_array('admin/plan/update', $permission)) {
            return redirect('admin/plans')->with('error', 'No permission To Update Plan');
        }

        return view('admin/plan/update', compact('permission', 'model'));
    }

    /**
     * Save or update plan data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        return response()->json((new Plan())->store($request->all()));
    }

    /**
     * View a specific plan's details along with logs and devices.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function view(Request $request)
    {
        $id = $request->input('id');
        $model = Plan::where('id', $id)->first();

        return view('admin/plan/view', compact('model'));
    }

    /**
     * Delete a specific plan.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $model = Plan::find($request->input('id'));
        if (!$model) {
            return response()->json(['status' => 0, 'message' => 'No data found']);
        }
        $model->delete();
        return response()->json(['status' => 1, 'message' => 'Plan deleted successfully.', 'next' => 'table_refresh']);
    }

    /**
     * Change the status of a plan.
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
        $model = Plan::find($id);

        if (!$model) {
            return response()->json(['status' => 0, 'message' => 'Plan not found']);
        }

        $model->update(['status' => !$model->status]);  // Toggle the status
        return response()->json(['status' => 1, 'message' => 'Plan status updated successfully.', 'next' => 'refresh']);
    }
}
