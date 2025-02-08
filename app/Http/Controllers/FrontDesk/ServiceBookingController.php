<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceJobs;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ServiceBookingController extends Controller
{
    public function index(){
        // return "Service Booking Page";
        $customers = Customer::all();
        $vehicles = Vehicle::all();

        return view('service-bookings.index', compact('vehicles', 'customers'));
    }


    public function createJob(Request $request)
    {
        try {
            // Log the incoming request data
            Log::info('Create Job Request:', $request->all());

            // Validate the incoming request
            $validated = $request->validate([
                'customer_id' => 'required',
                'vehicle_id' => 'required|exists:mysql_non_laravel.vehicles,vec_id',
                'order_number' => 'required|unique:mysql_non_laravel.service_jobs,order_number',
                'description' => 'nullable|string',
                'other_details' => 'nullable|array',
            ]);

            // ServiceJobs::doesCustomerExistInExternalDb($validated['customer_id']);
            // Verify customer exists in the external database
            if (!ServiceJobs::doesCustomerExistInExternalDb($validated['customer_id'])) {
                Log::info('Customer does not exist in the external database');
                return response()->json(['message' => 'Customer does not exist in the external database'], 404);
            }

            Log::info('Vehicle does not exist in the external database');
            if (!ServiceJobs::doesVehicleExistInExternalDb($validated['vehicle_id'])) {
                Log::info('Vehicle does not exist in the external database');
                return response()->json(['message' => 'Vehicle does not exist in the external database'], 404);
            }

            // // Create the job
            $job = new ServiceJobs();

            $job->customerId = $validated['customer_id'];
            $job->vehicleId = $validated['vehicle_id'];
            $job->order_number = $validated['order_number'];
            $job->job_start_date = now();
            $job->description = $validated['description'] ?? null;
            $job->other_details = $validated['other_details'] ?? null;
            $job->workflow = [
                [
                    'job_type' => 'diagnostics', // Initial job type
                    'date' => now()->toDateTimeString(), // Current timestamp
                    'details' => 'Initial diagnostics started', // Optional details
                ],
            ]; // Initial stage of the workflow
            $job->status = 'pending'; // Default status

            $job->save();

            // Log the successful creation of the job
            Log::info('Job Created Successfully:', $job->toArray());

            return response()->json([
                'message' => 'Job created successfully!',
                'job' => $job,
            ], 201);

        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Error Creating Job:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return a JSON response with an error message
            return response()->json([
                'message' => 'Failed to create job. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateWorkflow($jobId, $newAction)
    {
        try {
            $job = ServiceJobs::findOrFail($jobId); // Retrieve the job or throw an error

            // Append the new action to the workflow
            $workflow = $job->workflow; // Get existing workflow
            $workflow[] = [
                'job_type' => $newAction['job_type'], // Action type
                'date' => now()->toDateTimeString(), // Current timestamp
                'details' => $newAction['details'], // Additional details
            ];

            $job->workflow = $workflow; // Update workflow
            $job->save(); // Save the updated job

            return response()->json(['message' => 'Workflow updated successfully', 'workflow' => $job->workflow], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update workflow', 'error' => $e->getMessage()], 500);
        }
    }

    public function generateOrderNumber(){
        try {
            // Fetch the count of all jobs
            // $jobCount = DB::connection('mysql_non_laravel') // Use the correct connection
            //     ->table('service_jobs')
            //     ->count();
            $jobCount = ServiceJobs::count();

            return response()->json(['count' => $jobCount], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch job count', 'error' => $e->getMessage()], 500);
        }
    }

    public function returnJobController(){
        // return "Return Job Page";
        $jobs = ServiceJobs::get();
        $technicians = User::where('user_role', 'technician')->get();
        // return $jobs;

        return view('service-bookings.job_controller', compact('jobs', 'technicians'));
    }

    public function assignTechnician(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'technician_id' => 'required|exists:users,id',
        ]);

        try {
            $job = $this->updateWorkflow($request->job_id, [
                'job_type' => 'assigned',
                'details' => 'Technician assigned to job',
            ]);

            return $job;

            return redirect()->back()->with('success', 'Technician assigned successfully!');
        } catch (\Exception $e) {
            Log::error('Error assigning technician: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to assign technician.');
        }
    }
}
