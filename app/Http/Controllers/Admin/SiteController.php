<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\GeneralService;
/**
 * Class SiteController
 * 
 * Controller for handling admin site functionalities such as dashboard statistics and chart data.
 */
class SiteController extends Controller
{
    /**
     * Display the admin dashboard with user statistics.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $totalUser = User::where('role', 2)->count();
        $activeUser = User::where('role', 2)->where('status', 1)->count();
        $deactiveUser = User::where('role', 2)->where('status', 0)->count();
        return view('admin.site.dashboard', compact('totalUser', 'activeUser', 'deactiveUser'));
    }

    /**
     * Get user chart data based on the selected duration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartUser(Request $request)
    {
        $duration = $request->input('type');
        $userChartData = [];

        switch ($duration) {
            case 'day':
                $userChartData = (new GeneralService())->getUserLast7DaysChartData();
                break;
            case 'month':
                $userChartData = (new GeneralService())->getUserLast6MonthsChartData();
                break;
            default:
                $userChartData = (new GeneralService())->getUserMonthlyChartData();
                break;
        }

        return response()->json($userChartData);
    }

    /**
     * Display the user chart view.
     *
     * @return \Illuminate\View\View
     */
    public function getChartUser2()
    {
        return view("admin.site.userchart");
    }
}
