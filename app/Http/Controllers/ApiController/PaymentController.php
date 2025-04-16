<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\ServiceJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'invoice_no' => 'required|string',
    //     ]);

    //     // Add logs and handle exceptions
    //     try {
    //         Log::info('Processing payment store request', ['invoice_no' => $request->input('invoice_no')]);

    //         // Get invoice_no from request and check the service job for it
    //         $invoice_no = $request->input('invoice_no');
    //         $service_job = ServiceJobs::where('order_number', $invoice_no)->with('customer')->first();

    //         if (!$service_job) {
    //             Log::warning('Invoice not found', ['invoice_no' => $invoice_no]);
    //             return response()->json(['message' => 'Invoice not found'], 404);
    //         }

    //         // $data = json_decode($service_job, true);

    //         $customerName = $service_job['customer']['cust_name'];

    //         $estimatedJobs = json_decode($service_job['estimated_jobs'], true);

    //         $subTotal = $estimatedJobs['grand_total'];
    //         $vatTotal = $estimatedJobs['vat_amount'];
    //         $totalPayableAmount = $estimatedJobs['total_cost'];


    //         $sparePartsTotal = collect($estimatedJobs['spare_parts'])->sum('total_price');
    //         $servicesTotal = collect($estimatedJobs['services'])->sum('total_price');

    //         Log::info('Invoice found', ['service_job' => $service_job]);
    //         return response()->json([
    //             'invoice_no' => $service_job['order_number'],
    //             'customer_name' => $customerName,
    //             'spare_parts_total' => $sparePartsTotal,
    //             'services_total' => $servicesTotal,
    //             'grand_total' => $subTotal,
    //             'vat_total' => $vatTotal,
    //             'total_payable_amount' => $totalPayableAmount,
    //         ]);

    //         // return response()->json(['service_job' => $service_job], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Error processing payment store request', [
    //         'error' => $e->getMessage(),
    //         'invoice_no' => $request->input('invoice_no'),
    //         ]);
    //         return response()->json(['message' => 'An error occurred while processing the request'], 500);
    //     }
    // }

    public function store(Request $request)
    {
        // Validate the request input
        $request->validate([
            'invoice' => 'required|string',
        ]);

        try {
            Log::info('Processing payment store request', ['invoice_no' => $request->input('invoice_no')]);

            // Get invoice number from the request and lookup the service job by order_number
            $invoice = $request->input('invoice');
            $service_job = ServiceJobs::where('order_number', $invoice)->with('customer')->first();

            if (!$service_job) {
                Log::warning('Invoice not found', ['invoice' => $invoice]);
                return response()->json([
                    "status" => 0,
                    "response_code" => 404,
                    "message" => 'Invoice not found'
                ], 404);
            }

            // Extract the customer name from the related customer object
            $customerName = $service_job->customer->cust_name;

            // Decode the estimated_jobs JSON string
            $estimatedJobs = json_decode($service_job->estimated_jobs, true);

            // Extract the totals from the decoded estimated jobs
            $subTotal = $estimatedJobs['grand_total'];        // often the post-discount total (invoice total)
            $vatTotal = $estimatedJobs['vat_amount'];
            $totalPayableAmount = $estimatedJobs['total_cost'];  // full cost including VAT

            // Calculate spare parts and services totals
            $sparePartsTotal = collect($estimatedJobs['spare_parts'])->sum('total_price');
            $servicesTotal = collect($estimatedJobs['services'])->sum('total_price');

            // If you track payments made for the invoice, set the total already paid.
            // For now, if no payment tracking is available, we assume 0.
            $totalAlreadyPaid = 0;

            Log::info('Invoice found', ['service_job' => $service_job]);

            // Return a formatted JSON response with all details
            return response()->json([
                "status" => 1,
                "response_code"  => 200,
                "message"        => 'Details Found',
                "data"           => [
                    "invoice"              => $service_job->order_number,
                    "total"                => round($totalPayableAmount - $totalAlreadyPaid),
                    "total_invoice"        => $totalPayableAmount,
                    "total_already_paid"   => $totalAlreadyPaid,
                    "service"              => $servicesTotal,
                    "spare"                => $sparePartsTotal,
                    "Description"          => 'Payment for Invoice ' . $service_job->order_number,
                    "customer_name"        => $customerName,
                    "vat_total"            => $vatTotal
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error processing payment store request', [
                'error'      => $e->getMessage(),
                'invoice_no' => $request->input('invoice_no'),
            ]);
            return response()->json([
                "status"        => 0,
                "response_code" => 500,
                "message"       => 'An error occurred while processing the request'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
