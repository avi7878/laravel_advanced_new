<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * Class OrderController
 * @package App\Http\Controllers\Admin
 *
 * Handles Order management functionalities in the admin panel.
 */
class OrderController extends Controller
{
    /**
     * Display the Order index view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $sessionUser = auth()->user();
        if ($sessionUser->hasPermission('admin/order')) {
            return view('admin/order/index');
        } else {
            return redirect('admin/dashboard')->with('error', 'You are not authorized');
        }
    }

    /**
     * Get a list of Orders.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {

        return response()->json((new Order())->list($request->all()));
    }

    /**
     * Save or update Order data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        return response()->json((new Order())->store($request->all()));
    }

    /**
     * View a specific Order's details along with logs and devices.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function view(Request $request)
    {
        $id = $request->input('id');

        $model = DB::table('orders')
            ->select(
                'orders.*',
                'user.first_name',
                'user.last_name',
                'orders.transaction_id',
                'product.title as title'
            )
            ->leftJoin('transaction', 'orders.transaction_id', '=', 'transaction.id')
            ->leftJoin('user', 'transaction.user_id', '=', 'user.id')
            ->leftJoin('product', 'orders.product_id', '=', 'product.id')
            ->where('orders.id', $id)
            ->first();
        return view('admin/order/view', compact('model'));
    }
}
