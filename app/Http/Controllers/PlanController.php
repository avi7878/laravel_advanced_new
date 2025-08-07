<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanController extends Controller
{
    /**
     * Display the note index page.
     *
     * @return \Illuminate\View\View
     */
        public function index()
    {
        $plans = Plan::where('status',1)->get();
        return view('plan.index', compact('plans'));
    }
}
