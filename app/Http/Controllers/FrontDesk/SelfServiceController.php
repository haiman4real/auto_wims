<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\SelfServiceBookings;
use Illuminate\Http\Request;

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
        return view('self-service.show', compact('booking'));
    }
}

