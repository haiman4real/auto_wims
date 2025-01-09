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

}
