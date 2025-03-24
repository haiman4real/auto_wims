<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\SelfServiceBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SelfServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = SelfServiceBookings::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query->whereBetween('pickup_date', [$startDate, $endDate]);
        }

        $bookings = $query->get();

        // return $bookings;

        return view('self-service.index', compact('bookings'));
    }

    public function showBooking($id, Request $request)
    {
        $booking = SelfServiceBookings::findOrFail($id);

        // Return a partial view if the request is via AJAX.
        if ($request->ajax()) {
            return view('self-service.partials.booking-detail', compact('booking'));
        }

        // Otherwise, return a full page view if needed.
        return view('self-service.partials.booking-detail', compact('booking'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'                         => 'required|string|max:255',
            'email'                        => 'required|email',
            'phone'                        => 'required|string|max:15',
            'service_location.type'        => 'required|string|in:Home,Office,Other',
            'service_location.address'     => 'nullable|string|max:255',
            'service_location.latitude'    => 'nullable|numeric',
            'service_location.longitude'   => 'nullable|numeric',
            'home_address'                 => 'required|string',
            'pickup_date'                  => 'required|date',
            'pickup_time'                  => 'required',
            'vehicle_make'                 => 'required|string|max:255',
            'vehicle_make_manual'          => 'nullable|string|max:255',
            'vehicle_model'                => 'required|string|max:255',
            'vehicle_model_manual'         => 'nullable|string|max:255',
            'vehicle_year'                 => 'required|digits:4',
            'vin'                          => 'required|string|max:17',
            'license_plate'                => 'required|string|max:8',
            'service'                      => 'required|string',
            'other_service_details'        => 'nullable|string',
            'additional_details'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Validation Error', ['error' => $validator->errors()]);
            return redirect()->back()
                   ->withErrors($validator)
                   ->withInput();
        }

        // Get the validated data
        $validated = $validator->validated();

        // Optional guest booking: set user_id if authenticated
        $validated['user_id'] = Auth::check() ? Auth::id() : null;

        // Set a default response (will be overwritten below if file upload occurs)
        $validated['response'] = json_encode(['status' => 'pending']);

        $vehicleMake = $validated['vehicle_make'] === 'Other'
            ? $validated['vehicle_make_manual']
            : $validated['vehicle_make'];

        $vehicleModel = $validated['vehicle_model'] === 'Other'
            ? $validated['vehicle_model_manual']
            : $validated['vehicle_model'];

        $transaction_id = 'AQF-' . strtoupper(Str::random(12));

        // Build the response to include file details if file was uploaded
        $validated['response'] = json_encode([
            'transaction_id' => $transaction_id
        ]);

        // Create the booking using the "mysql_bookings" connection
        $booking = SelfServiceBookings::on('mysql_bookings')->create([
            'name'                => $validated['name'],
            'phone'               => $validated['phone'],
            'email'               => $validated['email'],
            'vehicle_make'        => $vehicleMake,
            'vehicle_model'       => $vehicleModel,
            'home_address'        => $validated['home_address'],
            'pickup_date'         => $validated['pickup_date'],
            'pickup_time'         => $validated['pickup_time'],
            'vehicle_year'        => $validated['vehicle_year'],
            'vin'                 => $validated['vin'],
            'license_plate'       => $validated['license_plate'],
            'service'             => $validated['service'],
            'service_location'    => [
                'type'     => $validated['service_location']['type'],
                'address'  => $validated['service_location']['address'] ?? null,
                'latitude' => $validated['service_location']['latitude'] ?? null,
                'longitude'=> $validated['service_location']['longitude'] ?? null,
            ],
            'other_service_details' => $validated['service'] === 'Other services' ? $validated['other_service_details'] : null,
            'additional_details'    => $validated['additional_details'],
            'response'              => $validated['response'],
        ]);

        // Redirect back or to a confirmation page
        return redirect()->route('self-service.index')->with('success', 'Booking created successfully.');
    }
}

