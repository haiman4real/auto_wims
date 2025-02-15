<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $connection = 'mysql_non_laravel';
    protected $primaryKey = 'cust_id';
    public $timestamps = false; // Set this if the table does not use timestamps
    protected $fillable = [
        'cust_name', 'cust_email', 'cust_mobile', 'cust_address', 'cust_lga', 'cust_mode', 'cust_type', 'cust_reg_time',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'cust_id', 'cust_id');
    }

}
