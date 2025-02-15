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

