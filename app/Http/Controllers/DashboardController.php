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
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'day'); // Default to 'day' if no filter is selected

        switch ($filter) {
            case 'week':
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
            break;
            case 'month':
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            break;
            case 'year':
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
            break;
            case 'day':
            default:
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            break;
        }

        // $endDate = Carbon::now()->toDateTimeString();

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
            // Get current week's counts
            $customerCount = DB::connection('mysql_non_laravel')
            ->table('customers')
            ->whereBetween('cust_reg_time', [$startDate, $endDate])
            ->get()->count();

            $vehicleCount = DB::connection('mysql_non_laravel')
                ->table('vehicles')
                ->whereBetween('vec_reg_time', [$startDate, $endDate])
                ->get()->count();

            $jobCount = DB::connection('mysql_non_laravel')
                ->table('jobs')
                ->whereBetween('job_reg_time', [$startDate, $endDate])
                ->get()->count();

            $invoiceCount = DB::connection('mysql_non_laravel')
                ->table('invoices')
                ->whereBetween('inv_time', [$startDate, $endDate])
                ->get()->count();

            $bookingJobs = $this->getBookingsJobsDetails();

            // Get previous week's counts
            $previousWeekStart = Carbon::now()->subWeek()->startOfWeek();
            $previousWeekEnd = Carbon::now()->subWeek()->endOfWeek();
            // Get previous period's counts for growth calculation
            switch ($filter) {
                case 'week':
                    $previousPeriodStart = Carbon::now()->subWeek()->startOfWeek();
                    $previousPeriodEnd = Carbon::now()->subWeek()->endOfWeek();
                    break;
                case 'month':
                    $previousPeriodStart = Carbon::now()->subMonth()->startOfMonth();
                    $previousPeriodEnd = Carbon::now()->subMonth()->endOfMonth();
                    break;
                case 'year':
                    $previousPeriodStart = Carbon::now()->subYear()->startOfYear();
                    $previousPeriodEnd = Carbon::now()->subYear()->endOfYear();
                    break;
                case 'day':
                default:
                    $previousPeriodStart = Carbon::now()->subDay()->startOfDay();
                    $previousPeriodEnd = Carbon::now()->subDay()->endOfDay();
                    break;
            }
            // $previousPeriodEnd = $startDate;

            $previousCustomerCount = DB::connection('mysql_non_laravel')
                ->table('customers')
                ->whereBetween('cust_reg_time', [$previousPeriodStart, $previousPeriodEnd])
                ->get()->count();

            $previousVehicleCount = DB::connection('mysql_non_laravel')
                ->table('vehicles')
                ->whereBetween('vec_reg_time', [$previousPeriodStart, $previousPeriodEnd])
                ->get()->count();

            $previousJobCount = DB::connection('mysql_non_laravel')
                ->table('jobs')
                ->whereBetween('job_reg_time', [$previousPeriodStart, $previousPeriodEnd])
                ->get()->count();

            $previousInvoiceCount = DB::connection('mysql_non_laravel')
                ->table('invoices')
                ->whereBetween('inv_time', [$previousPeriodStart, $previousPeriodEnd])
                ->get()->count();

            // Get total counts for each category
            $totalCustomerCount = DB::connection('mysql_non_laravel')
                ->table('customers')
                ->get()->count();

            $totalVehicleCount = DB::connection('mysql_non_laravel')
                ->table('vehicles')
                ->get()->count();

            $totalJobCount = DB::connection('mysql_non_laravel')
                ->table('jobs')
                ->get()->count();

            $totalInvoiceCount = DB::connection('mysql_non_laravel')
                ->table('invoices')
                ->get()->count();

            // Calculate percentage growth
            $customerGrowth = $this->calculateGrowth($previousCustomerCount, $customerCount);
            $vehicleGrowth = $this->calculateGrowth($previousVehicleCount, $vehicleCount);
            $jobGrowth = $this->calculateGrowth($previousJobCount, $jobCount);
            $invoiceGrowth = $this->calculateGrowth($previousInvoiceCount, $invoiceCount);

            // Return view with counts, total counts, and growth percentages
            return view('dashboard', compact(
                'customerCount', 'vehicleCount', 'jobCount', 'invoiceCount',
                'totalCustomerCount', 'totalVehicleCount', 'totalJobCount', 'totalInvoiceCount',
                'bookingJobs', 'customerGrowth', 'vehicleGrowth', 'jobGrowth', 'invoiceGrowth'
            ));
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

    public function getTransactionData()
    {
        // Get the total number of transactions per month
        $transactions = DB::connection('mysql_non_laravel')->table('invoices')
                                ->select(DB::raw('MONTH(FROM_UNIXTIME(inv_time)) as month'), DB::raw('COUNT(*) as total'))
                                ->where(DB::raw('YEAR(FROM_UNIXTIME(inv_time))'), date('Y')) // Filter by current year
                                ->groupBy(DB::raw('MONTH(FROM_UNIXTIME(inv_time))'))
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

    public function getCustomerLgaData()
    {
        // Retrieve the count of customers grouped by LGA
        $lgaData = DB::connection('mysql_non_laravel')
            ->table('customers')
            ->select(DB::raw('cust_lga as lga'), DB::raw('COUNT(*) as total'))
            ->whereNotNull('cust_lga')  // Exclude NULL LGAs
            ->where('cust_lga', '!=', '') // Exclude empty LGAs
            ->groupBy('cust_lga')
            ->get();

        // Prepare the labels and data for the pie chart
        $labels = [];
        $data = [];

        foreach ($lgaData as $lga) {
            $labels[] = $lga->lga;  // LGA names
            $data[] = $lga->total;  // Count of customers in each LGA
        }

        // Return the data as a JSON response
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }


    private function getBookingsJobsDetails()
    {
        // Fetch and join tables in a single query, using LEFT JOINs to handle nulls where necessary
        $bookingJobs = DB::connection('mysql_non_laravel')
            ->table('bookings_jobs as bj')
            ->join('bookings as b', 'bj.bookings_id', '=', 'b.bookings_id')
            ->leftJoin('jobs as j', 'bj.jobs_id', '=', 'j.jobs_id')
            ->leftJoin('customers as c', 'bj.cust_id', '=', 'c.cust_id')
            ->leftJoin('vehicles as v', 'bj.vec_id', '=', 'v.vec_id')
            ->leftJoin('invoices as i', 'bj.inv_id', '=', 'i.inv_id')
            ->leftJoin('payments as p', 'i.inv_ref_code', '=', 'p.pay_trans_id')
            ->select(
                'bj.bookings_id',
                'bj.jobs_id',
                'bj.cust_id',
                'bj.vec_id',
                'bj.inv_id',
                'bj.station_id',
                'b.bookings_desc',
                'b.bookings_status',
                DB::raw('IFNULL(FROM_UNIXTIME(b.bookings_reg_time, "%d %b, %Y %h:%i %p"), "Unknown Time") as bookings_time'),
                'b.bookings_ref',
                DB::raw('HEX(b.bookings_ref) as ref_id'),  // Use HEX() to convert binary to hexadecimal
                'j.serv_advisor_note as advisor_note',
                'c.cust_name',
                'v.vec_make',
                'v.vec_model',
                'p.pay_status',
                DB::raw('IF(p.pay_status = "completed", "completed", "pending") as inv1_status')
            )
            ->orderByDesc('bj.id')
            ->limit(100)
            ->get();

        // Process the results and map any station IDs to their names
        $result = $bookingJobs->map(function ($item) {
            return [
                'bookings_id' => $item->bookings_id ?? 'N/A',
                'jobs_id' => $item->jobs_id ?? 'N/A',
                'cust_id' => $item->cust_id ?? 'N/A',
                'vec_id' => $item->vec_id ?? 'N/A',
                'inv_id' => $item->inv_id ?? 'N/A',
                'station_id' => $item->station_id ?? 'N/A',
                'bookings_desc' => $item->bookings_desc ?? 'N/A',
                'bookings_status' => $item->bookings_status ?? 'Unknown',
                'bookings_time' => $item->bookings_time ?? 'Unknown Time',
                'bookingref' => $item->bookings_ref ?? 'N/A',
                'ref_id' => $item->ref_id ?? 'N/A',
                'advisor_note' => $item->advisor_note ?? 'No notes available',
                'cust_name' => $item->cust_name ?? 'Unknown Customer',
                'vec_make' => $item->vec_make ?? 'Unknown Make',
                'vec_model' => $item->vec_model ?? 'Unknown Model',
                'payment_status' => $item->inv1_status,
                'station' => $this->getStationName($item->station_id),
            ];
        });

        return $result;
    }

    private function getStationName($station_id)
    {
        return match ($station_id) {
            '1' => 'HQ',
            '2' => 'Ojodu',
            '3' => 'Egbeda',
            default => 'Unknown Station',
        };
    }

    private function calculateGrowth($previousCount, $currentCount)
    {
        if ($previousCount == 0) {
            return $currentCount > 0 ? 100 : 0; // Handle division by zero
        }

        return (($currentCount - $previousCount) / $previousCount) * 100;
    }


}
