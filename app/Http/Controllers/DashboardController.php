<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        if(Auth::user()->user_role === 'CoporateUser'){
            $agentId = Auth::id();

            $trackerAppt = DB::connection('mysql_non_laravel')
                                ->table('tracker_bookings')
                                ->where('agent_id', Auth::id())
                                ->get()->count();
            $vehicles = DB::connection('mysql_non_laravel')
                            ->table('customers_vehicles')
                            ->join('customers', 'customers_vehicles.cust_id', '=', 'customers.cust_id') // Assuming you have a customers table
                            ->join('vehicles', 'customers_vehicles.vec_id', '=', 'vehicles.vec_id') // Assuming you have a vehicles table
                            ->where(function ($query) {
                                $query->where('customers.cust_email', Auth::user()->email);
                            })
                            ->get(['vehicles.*']); // Select all vehicle columns


            $vehicleCount = $vehicles->count(); // Get the count of vehicles
            $repairJobs = 0;
            $awaitingApproval = 0;

            $recentActivities = DB::connection('mysql_non_laravel')->table('tracker_bookings')
                                    ->select('created_at', 'appointment_date', 'appointment_time', 'fullname') // Replace 'tracking_info' with the actual column name
                                    ->where('agent_id', Auth::id())
                                    ->orderBy('created_at', 'desc')
                                    ->limit(6) // Limit to the latest 6 activities
                                    ->get();

            // return $recentActivities;

            return view('dashboards.coporateDashboard', compact('trackerAppt', 'vehicleCount', 'repairJobs', 'awaitingApproval', 'recentActivities'));
        }else{
            return view('dashboard');
            // return view('dashboard')->with(["totalCompletedInMonth"=> $totalCompletedInMonth, "totalCompletedEachDay" => $totalCompletedEachDay, "totalInspectionsInMonth" => $totalInspectionsInMonth, "totalNotCompletedInMonth" => $totalNotCompletedInMonth]);
        }
    }

    public function getChartData()
    {
        // Get the total number of transactions per month
        $transactions = DB::connection('mysql_non_laravel')->table('tracker_bookings')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
            ->where('agent_id', Auth::id())
            ->groupBy('month')
            ->get();

        // Prepare the data for the chart (initialize all months with 0)
        $monthlyData = array_fill(1, 12, 0);

        // Fill the data for months that have transactions
        foreach ($transactions as $transaction) {
            $monthlyData[$transaction->month] = $transaction->total;
        }

        // Return the data in JSON format to the view
        return response()->json([
            'months' => array_values($monthlyData), // Monthly transaction counts
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        ]);
    }

    private function menuHasAccess($menu, $user)
    {
        // Check if user has role access
        $hasRoleAccess = $menu->roles->pluck('name')->intersect($user->getRoleNames())->isNotEmpty();

        // Check if user has permission access
        $hasPermissionAccess = $menu->permissions->pluck('name')->intersect($user->getAllPermissions()->pluck('name'))->isNotEmpty();

        // Check if any submenus have access
        $hasSubmenuAccess = $menu->submenus->filter(function ($submenu) use ($user) {
            return $this->menuHasAccess($submenu, $user);
        })->isNotEmpty();

        return $hasRoleAccess || $hasPermissionAccess || $hasSubmenuAccess;
    }
}
