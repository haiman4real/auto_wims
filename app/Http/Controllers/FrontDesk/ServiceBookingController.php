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
use PhpParser\Node\Expr\FuncCall;

class ServiceBookingController extends Controller
{
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
        $jobs = ServiceJobs::orderBy('created_at', 'desc')->get();
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
            Log::info("Job ID {$request->job_id} updated by user ID " . Auth::id(), [
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
            Log::info("Job ID {$request->job_id} updated by user ID " . Auth::id(), [
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
        $services = JobServices::where('serv_status', 'visible')->get();
        $spareParts = JobSpareParts::getSparePartsWithPrices();
        // return $spareParts;
        // return $services;
        $job = ServiceJobs::findOrFail($jobId);
        // return $job;
        return view('service-bookings.estimate', compact('job', 'services', 'spareParts'));

    }

    public function storeEstimation(Request $request)
    {
        try {
            // Log the raw request data for debugging.
            Log::info('Raw Request Data:', ['request' => $request->all()]);

            // Ensure items are properly decoded.
            $items = is_string($request->items) ? json_decode($request->items, true) : $request->items;

            // Ensure items is an actual array.
            if (!is_array($items)) {
                Log::error('Invalid items format:', ['items' => $request->items]);
                return response()->json(['message' => 'Invalid items format.'], 400);
            }

            $job = ServiceJobs::findOrFail($request->job_id);

            $services = [];
            $spareParts = [];
            $grandTotal = 0;
            $vatRate = 7.5; // VAT rate is 7.5%

            // Get the overall discount percentage from the request; default to 0 if not provided.
            $overallDiscountPercentage = $request->discount ?? 0;

            // Variable for accumulating the total value of service items (before discount).
            $totalServiceOriginal = 0;

            foreach ($items as $item) {
                $quantity = $item['quantity'];
                $price = $item['price'];

                // Calculate the line total without any discount.
                $lineTotal = $price * $quantity;

                if ($item['type'] === 'service') {
                    // For service items, accumulate the original total for discount calculations.
                    $totalServiceOriginal += $lineTotal;
                    $discount = 0; // No discount is applied at the line level.
                } else {
                    // Spare parts do not have any discount.
                    $discount = 0;
                }

                // Store the full line total without a discount deduction.
                $data = [
                    'id'          => $item['id'],
                    'name'        => $item['name'],
                    'price'       => $price,
                    'quantity'    => $quantity,
                    'discount'    => 0,         // Per-line discount is always 0.
                    'total_price' => $lineTotal, // Full total without discount.
                ];

                // Organize items into services or spare parts.
                if ($item['type'] === 'service') {
                    $services[] = $data;
                } else {
                    $spareParts[] = $data;
                }

                // Add the line total to the overall grand total.
                $grandTotal += $lineTotal;
            }

            // Calculate the overall discount amount based solely on the service total.
            $totalDiscountAmount = $totalServiceOriginal * ($overallDiscountPercentage / 100);

            // Deduct the discount amount from the overall grand total.
            $subTotalAfterDiscount = $grandTotal - $totalDiscountAmount;

            // Calculate VAT on the subtotal after discount.
            $vatAmount = ($subTotalAfterDiscount * $vatRate) / 100;
            $grandTotalWithVat = $subTotalAfterDiscount + $vatAmount;

            // Prepare the estimated jobs data including all calculated discount details.
            $job->estimated_jobs = json_encode([
                'services'              => $services,
                'spare_parts'           => $spareParts,
                'vat'                   => $vatRate,
                'vat_amount'            => $vatAmount,
                'grand_total'           => $grandTotal,            // Original total before discount.
                'total_cost'            => $grandTotalWithVat,     // Final total after applying discount and VAT.
                'total_discount_amount' => $totalDiscountAmount,   // Overall discount amount applied to services.
                'discount_percentage'   => $overallDiscountPercentage // Global discount percentage (for service items).
            ]);

            $job->status = 'estimate generated';
            $job->total_cost = $grandTotalWithVat;
            $job->save();

            return response()->json([
                'message'                => 'Estimate saved successfully!',
                'services'               => $services,
                'spare_parts'            => $spareParts,
                'grand_total'            => $grandTotal,
                'total_discount_amount'  => $totalDiscountAmount,
                'discount_percentage'    => $overallDiscountPercentage
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating job: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating job. Please try again.'], 500);
        }
    }

    public function returnInvoice($jobId){
        // return "Return Estimation Page";
        $job = ServiceJobs::findOrFail($jobId);
        // return $job;
        return view('service-bookings.download-invoice', compact('job'));
    }


    public function editEstimate($id)
    {
        $job = ServiceJobs::with('customer', 'vehicle')->findOrFail($id);
        $estimatedJobs = json_decode($job->estimated_jobs, true);
        $services = JobServices::where('serv_status', 'visible')->get();
        $spareParts = JobSpareParts::getSparePartsWithPrices();

        // return $spareParts;

        return view('service-bookings.estimate-edit', compact('job', 'estimatedJobs', 'services', 'spareParts'));
    }

    /**
     * Update the estimate for a service job.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateEstimate(Request $request)
    {
        try {
            $job = ServiceJobs::findOrFail($request->job_id);

            Log::info('Job request received for update: ', $request->all());

            $services = [];
            $spareParts = [];
            $grandTotal = 0;
            $vatRate = 7.5; // VAT rate is 7.5%

            // Retrieve the overall discount percentage from the request;
            // this discount applies only to service items.
            $overallDiscountPercentage = $request->discount ?? 0;

            // This variable will store the total of service items (before discount).
            $totalServiceOriginal = 0;

            // Loop through each item without applying discount at the line level.
            foreach ($request->items as $item) {
                $quantity = $item['quantity'];
                $price = $item['price'];

                // For service items, accumulate the total service value.
                if ($item['type'] === 'service') {
                    $totalServiceOriginal += ($price * $quantity);
                    $discount = 0; // Discount is not applied per line.
                } else {
                    // Spare parts (or non-service items) remain unchanged.
                    $discount = 0;
                }

                // Calculate the line total without discount.
                $totalPrice = $price * $quantity;

                $data = [
                    'id'          => $item['id'] ?? null, // If a new item, the ID might be null.
                    'name'        => $item['name'],
                    'price'       => $price,
                    'quantity'    => $quantity,
                    'discount'    => 0,            // Always zero at the line level.
                    'total_price' => $totalPrice,  // Full total, without discount deduction.
                ];

                // Organize items by their type.
                if ($item['type'] === 'service') {
                    $services[] = $data;
                } else {
                    $spareParts[] = $data;
                }

                // Add the line total to the overall total.
                $grandTotal += $totalPrice;
            }

            // Calculate the discount amount based solely on the service total.
            $totalDiscountAmount = $totalServiceOriginal * ($overallDiscountPercentage / 100);

            // Deduct the service discount from the overall grand total.
            $subTotalAfterDiscount = $grandTotal - $totalDiscountAmount;

            // Calculate VAT on the discounted subtotal.
            $vatAmount = ($subTotalAfterDiscount * $vatRate) / 100;

            // Final total cost including VAT.
            $grandTotalWithVat = $subTotalAfterDiscount + $vatAmount;

            // Store all details including discount values into the estimated_jobs column.
            $job->estimated_jobs = json_encode([
                'services'              => $services,
                'spare_parts'           => $spareParts,
                'vat'                   => $vatRate,
                'vat_amount'            => $vatAmount,
                'grand_total'           => $grandTotal,            // Original total without discount.
                'total_cost'            => $grandTotalWithVat,     // Total after discount and VAT.
                'total_discount_amount' => $totalDiscountAmount,   // Discount amount on service items.
                'discount_percentage'   => $overallDiscountPercentage // Overall discount percentage.
            ]);

            $job->status = 'estimate generated';
            $job->total_cost = $grandTotalWithVat;
            $job->save();

            Log::info('Estimate updated successfully for job ID: ' . $request->job_id);
            return response()->json(['message' => 'Estimate updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error updating estimate for job ID: ' . $request->job_id . ' - ' . $e->getMessage());
            return response()->json(['message' => 'Error updating estimate: ' . $e->getMessage()], 500);
        }
    }



    //Tehnicians Users
    public function returnTechnicianUser(){
        // return "Return Technician Page";
        // $jobs = ServiceJobs::whereJsonContains('workflow', [['job_type' => 'technician_assignment']])->whereJsonDoesntContain('workflow', [['job_type' => 'awaiting_job_advise']])->get();
        $loggedInUserId = Auth::id(); // Get the logged-in user ID

        $jobs = ServiceJobs::whereJsonContains('workflow', [['job_type' => 'technician_assignment']])
            ->whereJsonDoesntContain('workflow', [['job_type' => 'awaiting_job_advise']])
            ->where(function ($query) use ($loggedInUserId) {
                $query->whereJsonContains('workflow', [['job_type' => 'technician_assignment', 'performer' => $loggedInUserId]]);
            })
            ->get();

        // $technicians = User::where('user_role', 'technician')->get();
        // return $jobs;
        return view('service-bookings.technician_user', compact( 'jobs'));
    }


    public function updateTechnicianJobUser(Request $request)
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
                        'technician_id' => Auth::id(),
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
            Log::info("Job ID {$request->job_id} updated by user ID " . Auth::id(), [
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

    public function returnServiceAdvisorUser(){
        // return "Return Service Advisor Page";
        $loggedInUserId = Auth::id(); // Get the logged-in user ID

        $jobs = ServiceJobs::whereJsonContains('workflow', [['job_type' => 'awaiting_job_advise']])->whereJsonDoesntContain('workflow', [['job_type' => 'service_advisor_comments']])->where(function ($query) use ($loggedInUserId) {
            $query->whereJsonContains('workflow', [['job_type' => 'technician_assignment', 'performer' => $loggedInUserId]]);
        })
        ->get();;
        return view('service-bookings.advisor_user', compact('jobs'));
    }

}
