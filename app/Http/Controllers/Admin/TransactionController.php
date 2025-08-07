<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\General;

/**
 * Class TransactionController
 * @package App\Http\Controllers\Admin
 *
 * Handles Transaction management functionalities in the admin panel.
 */ 
class TransactionController extends Controller
{
    /**
     * Display the Transaction index view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        
        $sessionPlan = auth()->user();
        if ($sessionPlan->hasPermission('admin/transaction')) {
            return view('admin/transaction/index');
        } else {
            return redirect('admin/dashboard')->with('error', 'You are not authorized');
        }
    }

    /**
     * Get a list of Transactions.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        
        return response()->json((new Transaction())->list($request->all()));
    }

    /**
     * Save or update Transaction data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        return response()->json((new Transaction())->store($request->all()));
    }

    /**
     * View a specific Transaction's details along with logs and devices.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function view(Request $request)
    {
        $model = Transaction::find($request->input('id'));

        return view('admin/transaction/view', compact('model'));
    }
}
