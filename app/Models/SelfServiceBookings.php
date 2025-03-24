<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfServiceBookings extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $connection = 'mysql_bookings';
    protected $primaryKey = 'id';
    public $timestamps = false; // Set this if the table does not use timestamps

    protected $fillable = [
        'name', 'email', 'phone', 'pickup_location', 'home_address',
        'pickup_date', 'pickup_time', 'vehicle_make', 'vehicle_model', 'vehicle_year',
        'vin', 'license_plate', 'service', 'additional_details', 'response', 'other_service_details',
        'service_location'
    ];

    protected $casts = [
        'response' => 'array',
        'service_location' => 'array',
    ];

}
