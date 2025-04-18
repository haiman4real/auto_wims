<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\ServiceJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FrontDesk\ServiceBookingController;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $serviceBooking;
    public function __construct(ServiceBookingController $serviceBooking)
    {
        $this->serviceBooking = $serviceBooking;
    }
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Validate the request input
    //     $request->validate([
    //         'invoice' => 'required|string',
    //     ]);

    //     try {
    //         Log::info('Processing payment store request', ['invoice' => $request->input('invoice')]);

    //         // Get invoice number from the request and lookup the service job by order_number
    //         $invoice = $request->input('invoice');
    //         $service_job = ServiceJobs::where('order_number', $invoice)->with('customer')->first();

    //         if (!$service_job) {
    //             Log::warning('Invoice not found', ['invoice' => $invoice]);
    //             return response()->json([
    //                 "status" => 0,
    //                 "response_code" => 404,
    //                 "message" => 'Invoice not found'
    //             ], 404);
    //         }

    //         // Extract the customer name from the related customer object
    //         $customerName = $service_job->customer->cust_name;

    //         // Decode the estimated_jobs JSON string
    //         $estimatedJobs = json_decode($service_job->estimated_jobs, true);

    //         // Extract the totals from the decoded estimated jobs
    //         $subTotal = $estimatedJobs['grand_total'];        // often the post-discount total (invoice total)
    //         $vatTotal = $estimatedJobs['vat_amount'];
    //         $totalPayableAmount = $estimatedJobs['total_cost'];  // full cost including VAT

    //         // Calculate spare parts and services totals
    //         $sparePartsTotal = collect($estimatedJobs['spare_parts'])->sum('total_price');
    //         $servicesTotal = collect($estimatedJobs['services'])->sum('total_price');

    //         // If you track payments made for the invoice, set the total already paid.
    //         // For now, if no payment tracking is available, we assume 0.
    //         $totalAlreadyPaid = 0;

    //         //log the payment initialization
    //         Log::info('Payment initialization', [
    //             'job_id' => $service_job->id,
    //             'performer_id' => Auth::id(),
    //             'details' => [
    //                 'chanel' => 'api',
    //                 'total_amount' => $totalPayableAmount - $totalAlreadyPaid,
    //                 'customer' => $customerName,
    //             ],
    //         ]);
    //         $workflow = $this->serviceBooking->updateWorkflow($service_job->id, [
    //             'job_type' => 'payment_initialized',
    //             'performer_id' => Auth::id(),
    //             'details' =>
    //                 [
    //                     'chanel' => 'api',
    //                     'total_amount' => $totalPayableAmount - $totalAlreadyPaid,
    //                     'customer' => Auth::id(),
    //                 ],
    //         ]);
    //         Log::info('Workflow updated');

    //         Log::info('Invoice found', ['service_job' => $service_job]);

    //         // Return a formatted JSON response with all details
    //         return response()->json([
    //             "status" => 1,
    //             "response_code"  => 200,
    //             "message"        => 'Details Found',
    //             "data"           => [
    //                 "invoice"              => $service_job->order_number,
    //                 "Description"          => 'Payment for Invoice ' . $service_job->order_number,
    //                 "customer_name"        => $customerName,
    //                 "service"              => $servicesTotal,
    //                 "spare"                => $sparePartsTotal,
    //                 "vat_total"            => $vatTotal,
    //                 "total_already_paid"   => $totalAlreadyPaid,
    //                 "total_invoice"        => $totalPayableAmount,
    //                 "total_balance"        => $totalPayableAmount - $totalAlreadyPaid,
    //             ]
    //         ], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Error processing payment store request', [
    //             'error'      => $e->getMessage(),
    //             'invoice' => $request->input('invoice'),
    //         ]);
    //         return response()->json([
    //             "status"        => 0,
    //             "response_code" => 500,
    //             "message"       => 'An error occurred while processing the request'
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        try {
            Log::info('Processing payment store request', ['invoice' => $request->input('invoice')]);

            $request->validate([
            'invoice' => 'required|string',
            ]);

            $invoice = $request->input('invoice');

            // 1) Already exists?
            $payment = Payment::where('invoice', $invoice)->first();
            if ($payment) {
            $code = $payment->status === 'paid' ? 3 : 2;
            $msg  = $payment->status === 'paid'
                ? 'Invoice already completely paid'
                : 'Payment already initialized';

            Log::info('Payment already exists', ['invoice' => $invoice, 'status' => $payment->status]);

            return response()->json([
                'status'        => $code,
                'response_code' => 200,
                'message'       => $msg,
                'data'          => [
                    'invoice'        => $payment->invoice,
                    'customer_name'  => $payment->serviceJob->customer->cust_name,
                    'total_amount'   => $payment->total_amount,
                    'amount_paid'    => $payment->amount_paid,
                    'balance'        => $payment->total_amount - $payment->amount_paid,
                    'status'         => $payment->status,
                    'transaction_id' => $payment->transaction_id,
                ],
            ], 200);
            }

            // 2) Lookup service job
            $serviceJob = ServiceJobs::where('order_number', $invoice)
                ->with('customer')
                ->first();
            if (! $serviceJob) {
            Log::warning('Invoice not found', ['invoice' => $invoice]);

            return response()->json([
                'status'        => 0,
                'response_code' => 404,
                'message'       => 'Invoice not found',
            ], 404);
            }

            // 3) Calculate totals
            $jobs      = json_decode($serviceJob->estimated_jobs, true);
            $totalAmt  = $jobs['total_cost'];
            $paidSoFar = 0;

            Log::info('Service job found', [
            'invoice' => $invoice,
            'total_amount' => $totalAmt,
            'customer_name' => $serviceJob->customer->cust_name,
            ]);

            // 4) Create new record
            $new = Payment::create([
                'service_job_id'      => $serviceJob->id,
                'invoice'             => $invoice,
                'total_amount'        => $totalAmt,
                'amount_paid'         => $paidSoFar,
                'status'              => 'pending',
                'transaction_id'      => (string) Str::uuid(),
                'transaction_details' => [
                    'initiated_by' => Auth::id(),
                    'channel'      => 'api',
                ],
                'response_data'       => null,
                'metadata'            => [
                    'customer_name' => $serviceJob->customer->cust_name,
                ],
            ]);

            Log::info('Payment record created', [
                'invoice' => $new->invoice,
                'transaction_id' => $new->transaction_id,
                'status' => $new->status,
            ]);

            // 5) Return
            return response()->json([
                'status'        => 1,
                'response_code' => 200,
                'message'       => 'Payment initialized',
                'data'          => [
                    'invoice'        => $new->invoice,
                    'customer_name'  => $serviceJob->customer->cust_name,
                    'total_amount'   => $new->total_amount,
                    'amount_paid'    => $new->amount_paid,
                    'balance'        => $new->total_amount - $new->amount_paid,
                    'status'         => $new->status,
                    'transaction_id' => $new->transaction_id,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error processing payment store request', [
                'error'   => $e->getMessage(),
                'invoice' => $request->input('invoice'),
            ]);

            return response()->json([
                'status'        => 0,
                'response_code' => 500,
                'message'       => 'An error occurred while processing the request',
            ], 500);
        }
    }

    /**
     * Handle the payment gateway callback.
     */
    public function handleResponse(Request $request)
    {
        try {
            // 1) Validate incoming data
            $data = $request->validate([
                'transaction_id' => 'required|uuid',
                'reference'      => 'required|string',
                'gateway'        => 'required|string',
                'message'        => 'required|json',
                'amount'         => 'required|numeric',
            ]);

            // 3) Find the payment record
            $payment = Payment::where('transaction_id', $data['transaction_id'])->first();
            if (! $payment) {
                Log::warning('Payment record not found', ['transaction_id' => $data['transaction_id']]);
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Payment record not found.',
                ], 404);
            }

            // 4) If already fully paid, return early
            if ($payment->status === 'paid') {
                Log::info('Payment already completed', ['transaction_id' => $payment->transaction_id]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Payment already completed.',
                    'data'    => [
                    'transaction_id' => $payment->transaction_id,
                    'status'         => $payment->status,
                    'amount_paid'    => $payment->amount_paid,
                    'total_amount'   => $payment->total_amount,
                    ],
                ]);
            }

            // 5) Calculate new paid amount & status
            $incomingAmount = (float) $data['amount'];
            $newPaid        = $payment->amount_paid + $incomingAmount;
            $newStatus      = $newPaid >= $payment->total_amount ? 'paid' : 'partial';

            // 6) Append this callback to the response_data JSON array
            $responses = $payment->response_data ?? [];
            $responses[] = [
                'reference'   => $data['reference'],
                'gateway'     => $data['gateway'],
                'message'     => $data['message'],
                'amount'      => $incomingAmount,
                'received_at' => now()->toDateTimeString(),
            ];

            // 7) Persist updates
            $payment->update([
                'amount_paid'   => $newPaid,
                'status'        => $newStatus,
                'response_data' => $responses,
            ]);

            Log::info('Payment callback processed', [
                'transaction_id' => $payment->transaction_id,
                'new_status'     => $newStatus,
                'amount_paid'    => $newPaid,
            ]);

            // 8) Return JSON
            return response()->json([
                'status'  => 'success',
                'message' => 'Payment updated successfully.',
                'data'    => [
                    'transaction_id' => $payment->transaction_id,
                    'status'         => $payment->status,
                    'amount_paid'    => $payment->amount_paid,
                    'balance'        => $payment->total_amount - $payment->amount_paid,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing payment callback', [
                'error'          => $e->getMessage(),
                'transaction_id' => $request->input('transaction_id'),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while processing the payment callback.',
            ], 500);
        }
    }
}
