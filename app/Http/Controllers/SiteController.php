<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AccountService;
use App\Services\AuthService;
use Illuminate\Http\Request;

/**
 * Class SiteController
 *
 * Controller for handling site-related actions such as user registration, email verification, and dashboard.
 */
class SiteController extends Controller
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
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function register()
    {
        return view('site/register');
    }

    /**
     * Handle the registration process.
     *
     * @param Request $request The incoming request.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerProcess(Request $request)
    {
        return response()->json((new AccountService())->registerProcess($request->all()));
    }
    
}
