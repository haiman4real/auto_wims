<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\SelfServiceBookings;
use Illuminate\Http\Request;

class SelfServiceController extends Controller
{
    public function index(){

        $bookings = SelfServiceBookings::get();
        // return $bookings;
        return view('self-service.index', compact('bookings'));
        // return view('self-service.index');
    }
}


// id": 31,
//     "user_id": null,
//     "name": "Emmanuel",
//     "email": "haiman4real@gmail.com",
//     "phone": "08012345678",
//     "service_location": "{\"type\": \"Home\", \"address\": \"hello motorj\", \"latitude\": null, \"longitude\": null}",
//     "home_address": "HFHFJHFJFJH",
//     "pickup_date": "2025-01-03",
//     "pickup_time": "15:48:00",
//     "vehicle_make": "T & B WELDING & TRAILERS",
//     "vehicle_model": "T & B Welding & Trailers",
//     "vehicle_year": "2021",
//     "vin": "GHDGDGDU01BCBNHJH",
//     "license_plate": "HDHDHJDH",
//     "service": "Suspension Repairs",
//     "other_service_details": null,
//     "additional_details": "HGD",
//     "response": null,
//     "created_at": "2024-12-19 19:05:04",
//     "updated_at": "2024-12-19 19:05:04",
//     "status": "pending"
