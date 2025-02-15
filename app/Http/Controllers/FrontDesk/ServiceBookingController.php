<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\JobServices;
use App\Models\JobSpareParts;
use App\Models\ServiceJobs;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceBookingController extends Controller
{
    private function updateWorkflow($jobId, $newAction)
    {
        try {
            $job = ServiceJobs::findOrFail($jobId); // Retrieve the job or throw an error

            // Append the new action to the workflow
            $workflow = $job->workflow; // Get existing workflow
            $workflow[] = [
                'job_type' => $newAction['job_type'], // Action type
                'date' => now()->toDateTimeString(), // Current timestamp
                'details' => $newAction['details'], // Additional details
                'performer' => $newAction['performer_id'], // Technician ID
            ];

            $job->workflow = $workflow; // Update workflow
            $job->save(); // Save the updated job

            return response()->json(['message' => 'Workflow updated successfully', 'workflow' => $job->workflow], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update workflow', 'error' => $e->getMessage()], 500);
        }
    }

    private function updateWorkNote($jobId, $notes)
    {
        try {
            $job = ServiceJobs::findOrFail($jobId); // Retrieve the job or throw an error

            // Append the new action to the workflow
            $worknotes = $job->work_notes; // Get existing workflow
            $worknotes[] = [
                'job_type' => $notes['job_type'], // Action type
                'date' => now()->toDateTimeString(), // Current timestamp
                'details' => $notes['details'], // Additional details
            ];

            $job->work_notes = $worknotes; // Update workflow
            $job->save(); // Save the updated job

            return response()->json(['message' => 'Work Notes updated successfully', 'workflow' => $job->work_notes], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update workflow', 'error' => $e->getMessage()], 500);
        }
    }

    public function returnJobBank(){
        // return "Return Job Bank Page";
        $jobs = ServiceJobs::all();
        // return $jobs;
        return view('service-bookings.job_bank', compact('jobs'));
    }

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
        // $jobs = ServiceJobs::get();
        $jobs = ServiceJobs::whereJsonDoesntContain('workflow', [['job_type' => 'technician_assignment']])->get();
        $jobAdvises = ServiceJobs::whereJsonContains('workflow', [['job_type' => 'awaiting_job_advise']])->get();
        $technicians_assigned = ServiceJobs::whereJsonContains('workflow', [['job_type' => 'technician_assignment']])->get();
        $technicians = User::where('user_role', 'technician')->get();
        // return $jobAdvises;

        return view('service-bookings.job_controller', compact('jobs', 'technicians', 'jobAdvises'));
    }

    public function returnTechnicianAdmin(){
        // return "Return Technician Page";
        $jobs = ServiceJobs::whereJsonContains('workflow', [['job_type' => 'technician_assignment']])->whereJsonDoesntContain('workflow', [['job_type' => 'awaiting_job_advise']])->get();
        $technicians = User::where('user_role', 'technician')->get();
        return view('service-bookings.technician_admin', compact('technicians', 'jobs'));
    }

    public function assignTechnician(Request $request)
    {
        $request->validate([
            'job_id' => 'required',
            'technician_id' => 'required|exists:users,id',
        ]);

        try {
            $this->updateWorkflow($request->job_id, [
                'job_type' => 'technician_assignment',
                'performer_id' => (int)$request->technician_id,
                'details' => 'Technician assigned to job',
            ]);

            return response()->json(['success' => true, 'message' => 'Technician assigned successfully!']);
        } catch (\Exception $e) {
            Log::error('Error assigning technician: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to assign technician.'], 500);
        }
    }


    public function updateTechnicianJobAdmin(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'job_id' => 'required',
            'workshop_findings' => 'required|string',
            'required_spare_parts' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        try {
            $this->updateWorkflow($request->job_id, [
                'job_type' => 'awaiting_job_advise',
                'performer_id' => Auth::id(),
                'details' =>
                    [
                        'workshop_findings' => $request->workshop_findings,
                        'required_spare_parts' => $request->required_spare_parts,
                    ],
            ]);

            $this->updateWorkNote($request->job_id, [
                'job_type' => 'technician_notes',
                'details' =>
                    [
                        'workshop_findings' => $request->workshop_findings,
                        'required_spare_parts' => $request->required_spare_parts,
                    ],
            ]);

            // Log the update
            Log::info("Job ID {$request->job_id} updated by user ID " . auth()->id(), [
                'technician_id' => Auth::id(),
                'workshop_findings' => $request->workshop_findings,
                'required_spare_parts' => $request->required_spare_parts,
            ]);

            return response()->json(['message' => 'Job updated successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('Error updating job: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating job. Please try again.'], 500);
        }
    }


    public function returnServiceAdvisor(){
        // return "Return Service Advisor Page";
        $jobs = ServiceJobs::whereJsonContains('workflow', [['job_type' => 'awaiting_job_advise']])->whereJsonDoesntContain('workflow', [['job_type' => 'service_advisor_comments']])->get();
        return view('service-bookings.advisor', compact('jobs'));
    }

    public function updateServiceAdvisor(Request $request){
        $request->validate([
            'job_id' => 'required',
            'service_advise' => 'required|string',
            'comments' => 'nullable|string',
        ]);
        try {
            $this->updateWorkflow($request->job_id, [
                'job_type' => 'service_advisor_comments',
                'performer_id' => Auth::id(),
                'details' => [
                    'service_advise' => $request->service_advise,
                    'comments' => $request->comments,
                ],
            ]);

            $this->updateWorkNote($request->job_id, [
                'job_type' => 'advisor_notes',
                'details' => [
                    'service_advise' => $request->service_advise,
                    'comments' => $request->comments,
                ],
            ]);

            $job = ServiceJobs::findOrFail($request->job_id);
            $job->status = 'in progress';
            $job->save();


            // Log the update
            Log::info("Job ID {$request->job_id} updated by user ID " . auth()->id(), [
                'service_advise' => $request->service_advise,
                'comments' => $request->comments,
                'status' => 'in progress',
            ]);
            return response()->json(['message' => 'Job approved for estimation successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('Error updating job: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating job. Please try again.'], 500);
        }

    }

    public function returnBookings(){
        // return "Return Bookings Page";
        $jobs = ServiceJobs::whereJsonContains('workflow', [['job_type' => 'service_advisor_comments']])->where('status', 'in progress')->orWhere('status', 'estimate generated')->get();
        return view('service-bookings.bookings', compact('jobs'));
    }

    public function showEstimationPage($jobId){
        $services = JobServices::all();
        $spareParts = JobSpareParts::getSparePartsWithPrices();
        // return $spareParts;
        // return $services;
        $job = ServiceJobs::findOrFail($jobId);
        // return $job;
        return view('service-bookings.estimate', compact('job', 'services', 'spareParts'));

    }

    // public function storeEstimation(Request $request){

    //     // $request->validate([
    //     //     'job_id' => 'required',
    //     //     'items' => 'required|array', // Ensure items is an array
    //     //     'items.*.id' => 'required|integer',
    //     //     'items.*.name' => 'required|string',
    //     //     'items.*.type' => 'required|in:service,spare_parts',
    //     //     'items.*.price' => 'required|numeric|min:0',
    //     //     'items.*.quantity' => 'required|integer|min:1',
    //     //     'items.*.discount' => 'nullable|numeric|min:0|max:100',
    //     //     'grand_total' => 'required|numeric|min:0'
    //     // ]);

    //     // // Ensure items is an actual array
    //     // $items = is_string($request->items) ? json_decode($request->items, true) : $request->items;

    //     // if (!is_array($items)) {
    //     //     return response()->json(['message' => 'Invalid items format.'], 400);
    //     // }

    //     // Ensure the request is JSON and decode it properly
    //     // $data = $request->isJson() ? $request->json()->all() : $request->all();
    //     // // return $data;

    //     // // Validate the request
    //     // $validated = Validator::make($data, [
    //     //     'job_id' => 'required',
    //     //     'items' => 'required|array', // Ensure items is an array
    //     //     'items.*.id' => 'required|integer',
    //     //     'items.*.name' => 'required|string',
    //     //     'items.*.type' => 'required|in:service,spare_parts',
    //     //     'items.*.price' => 'required|numeric|min:0',
    //     //     'items.*.quantity' => 'required|integer|min:1',
    //     //     'items.*.discount' => 'nullable|numeric|min:0|max:100',
    //     //     'grand_total' => 'required|numeric|min:0'
    //     // ]);

    //     // if ($validated->fails()) {
    //     //     return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 400);
    //     // }

    //     // Debugging: Log raw request data
    //     Log::info('Raw Request Data:', ['request' => $request->all()]);

    //     // Ensure the request is JSON and retrieve the request data properly
    //     $data = $request->json()->all(); // Use json() to ensure correct structure

    //     // Debugging: Log parsed JSON data
    //     Log::info('Parsed JSON Data:', ['data' => $data]);

    //     // Validate request data
    //     // $validator = Validator::make($data, [
    //     //     'job_id' => 'required',
    //     //     'items' => 'required|array|min:1',
    //     //     'items.*.id' => 'required|integer',
    //     //     'items.*.name' => 'required|string',
    //     //     'items.*.type' => 'required|in:service,spare_parts',
    //     //     'items.*.price' => 'required|numeric|min:0',
    //     //     'items.*.quantity' => 'required|integer|min:1',
    //     //     'items.*.discount' => 'nullable|numeric|min:0|max:100',
    //     //     'grand_total' => 'required|numeric|min:0'
    //     // ]);

    //     // // Debugging: Log validation errors if any
    //     // if ($validator->fails()) {
    //     //     Log::error('Validation Failed:', ['errors' => $validator->errors()]);

    //     //     return response()->json([
    //     //         'message' => 'Validation failed',
    //     //         'errors' => $validator->errors()
    //     //     ], 422); // 422 Unprocessable Entity
    //     // }

    //     // // Extract the validated data
    //     // $jobId = $data['job_id'];
    //     // $items = $data['items'];
    //     // $grandTotal = $data['grand_total'];

    //     // return $validator;

    //     try {
    //         $job = ServiceJobs::findOrFail($request->job_id);

    //         $services = [];
    //         $spareParts = [];
    //         $grandTotal = 0;

    //         foreach ($request->items as $item) {
    //             $quantity = $item['quantity'];
    //             $price = $item['price'];
    //             $discount = $item['discount'] ?? 0; // Default discount is 0%

    //             // Calculate total price after applying discount
    //             $discountAmount = ($price * $quantity) * ($discount / 100);
    //             $totalPrice = ($price * $quantity) - $discountAmount;

    //             $data = [
    //                 'id' => $item['id'],
    //                 'name' => $item['name'],
    //                 'price' => $price,
    //                 'quantity' => $quantity,
    //                 'discount' => $discount,
    //                 'total_price' => $totalPrice, // Price after discount
    //             ];

    //             if ($item['type'] === 'service') {
    //                 $services[] = $data;
    //             } else {
    //                 $spareParts[] = $data;
    //             }

    //             // Add to grand total
    //             $grandTotal += $totalPrice;
    //         }

    //         // Store in the database
    //         $job->estimated_jobs = json_encode([
    //             'services' => $services,
    //             'spare_parts' => $spareParts,
    //             'grand_total' => $grandTotal
    //         ]);
    //         $job->status = 'estimate generated';
    //         $job->total_cost = $grandTotal;
    //         $job->save();

    //         $this->updateWorkNote($request->job_id, [
    //             'job_type' => 'estimation_notes',
    //             'details' => [
    //                 'estimation' => $request->estimation,
    //             ],
    //         ]);
    //         // Log the update
    //         Log::info("Job ID {$request->job_id} updated by user ID " . auth()->id(), [
    //             'estimation' => $request->estimation,
    //             'status' => 'completed',
    //         ]);
    //         // return response()->json(['message' => 'Estimation saved successfully!'], 200);
    //         return response()->json([
    //             'message' => 'Estimate saved successfully!',
    //             'services' => $services,
    //             'spare_parts' => $spareParts,
    //             'grand_total' => $grandTotal
    //         ], 200);

    //     } catch (\Exception $e) {
    //         Log::error('Error updating job: ' . $e->getMessage());
    //         return response()->json(['message' => 'Error updating job. Please try again.'], 500);
    //     }

    // }


    public function storeEstimation(Request $request)
    {
        try {
            // Log the raw request data to debug
            Log::info('Raw Request Data:', ['request' => $request->all()]);

            // Ensure items are properly decoded
            $items = is_string($request->items) ? json_decode($request->items, true) : $request->items;

            // Ensure items is an actual array
            if (!is_array($items)) {
                Log::error('Invalid items format:', ['items' => $request->items]);
                return response()->json(['message' => 'Invalid items format.'], 400);
            }

            $job = ServiceJobs::findOrFail($request->job_id);

            $services = [];
            $spareParts = [];
            $grandTotal = 0;
            $vatRate = 7.5; // VAT rate is 7.5%

            foreach ($items as $item) {
                $quantity = $item['quantity'];
                $price = $item['price'];
                $discount = $item['discount'] ?? 0; // Default discount is 0%

                // Calculate total price after applying discount
                $discountAmount = ($price * $quantity) * ($discount / 100);
                $totalPrice = ($price * $quantity) - $discountAmount;

                $data = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $price,
                    'quantity' => $quantity,
                    'discount' => $discount,
                    'total_price' => $totalPrice, // Price after discount
                ];

                if ($item['type'] === 'service') {
                    $services[] = $data;
                } else {
                    $spareParts[] = $data;
                }

                // Add to grand total
                $grandTotal += $totalPrice;
            }

            // Calculate VAT amount
            $vatAmount = ($grandTotal * $vatRate) / 100;
            $grandTotalWithVat = $grandTotal + $vatAmount;

            // Store in the database
            $job->estimated_jobs = json_encode([
                'services' => $services,
                'spare_parts' => $spareParts,
                'vat' => $vatRate,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
                'total_cost' => $grandTotalWithVat
            ]);
            $job->status = 'estimate generated';
            $job->total_cost = $grandTotalWithVat;
            $job->save();

            return response()->json([
                'message' => 'Estimate saved successfully!',
                'services' => $services,
                'spare_parts' => $spareParts,
                'grand_total' => $grandTotal
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating job: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating job. Please try again.'], 500);
        }
    }

    public function returnInvoice($jobId){
        // return "Return Estimation Page";
        $job = ServiceJobs::findOrFail($jobId);
        // return $jobs;
        return view('service-bookings.invoice', compact('job'));
    }


    public function editEstimate($id)
    {
        $job = ServiceJobs::with('customer', 'vehicle')->findOrFail($id);
        $estimatedJobs = json_decode($job->estimated_jobs, true);
        $services = JobServices::all();
        $spareParts = JobSpareParts::getSparePartsWithPrices();

        // return $spareParts;

        return view('service-bookings.estimate-edit', compact('job', 'estimatedJobs', 'services', 'spareParts'));
    }

    public function updateEstimate(Request $request)
    {

        try {
            $job = ServiceJobs::findOrFail($request->job_id);


            Log::info('Job request received for update: ', $request->all());

            $services = [];
            $spareParts = [];
            $grandTotal = 0;
            $vatRate = 7.5; // VAT rate is 7.5%

            foreach ($request->items as $item) {
                $quantity = $item['quantity'];
                $price = $item['price'];
                $discount = $item['discount'] ?? 0; // Default discount is 0%

                // Calculate total price after applying discount
                $discountAmount = ($price * $quantity) * ($discount / 100);
                $totalPrice = ($price * $quantity) - $discountAmount;

                $data = [
                    'id' => $item['id'] ?? null, // If a new item, ID might be null
                    'name' => $item['name'],
                    'price' => $price,
                    'quantity' => $quantity,
                    'discount' => $discount,
                    'total_price' => $totalPrice, // Price after discount
                ];

                if ($item['type'] === 'service') {
                    $services[] = $data;
                } else {
                    $spareParts[] = $data;
                }

                // Add to grand total
                $grandTotal += $totalPrice;
            }

            // Calculate VAT amount
            $vatAmount = ($grandTotal * $vatRate) / 100;
            $grandTotalWithVat = $grandTotal + $vatAmount;

            // Store in the database with proper formatting
            $job->estimated_jobs = json_encode([
                'services' => $services,
                'spare_parts' => $spareParts,
                'vat' => $vatRate,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
                'total_cost' => $grandTotalWithVat
            ]);

            // Update job status and total cost
            $job->status = 'estimate generated';
            $job->total_cost = $grandTotalWithVat;
            $job->save();

            Log::info('Estimate updated successfully for job ID: ' . $request->job_id);
            return response()->json(['message' => 'Estimate updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating estimate: ' . $e->getMessage()], 500);
        }
    }
}
