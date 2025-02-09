<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceJobs extends Model
{
    use HasFactory;

    protected $table = 'service_jobs';
    protected $connection = 'mysql_non_laravel';

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'order_number',
        'job_start_date',
        'description',
        'other_details',
        'workflow',
        'status',
        'total_cost',
    ];

    protected $casts = [
        'other_details' => 'array',
        'workflow' => 'array',
    ];

    // Customer::on('mysql_non_laravel')->where('cust_id', $customerId)->exists();

    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }

    // public function vehicle()
    // {
    //     return $this->belongsTo(Vehicle::class);
    // }

    /**
     * Relationship: Job belongs to a Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId', 'cust_id', );
    }

    /**
     * Relationship: Job belongs to a Vehicle
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicleId', 'vec_id');
    }

    // /**
    //  * Check if a customer exists in an external database.
    //  *
    //  * @param int $customerId
    //  * @return bool
    //  */
    // public static function doesCustomerExistInExternalDb($customerId)
    // {
    //     return DB::connection('mysql_non_laravel')
    //         ->table('customers') // Update table name if needed
    //         ->where('cust_id', '==', $customerId)
    //         ->exists();
    // }

    /**
     * Check if a customer exists in an external database.
     *
     * @param int $customerId
     * @return bool
     */
    public static function doesCustomerExistInExternalDb($customerId)
    {
        return DB::connection('mysql_non_laravel')
            ->table('customers') // Ensure this matches the external database table name
            ->where('cust_id', $customerId) // Ensure this matches the external database column
            ->exists();

        // $customer =  DB::connection('mysql_non_laravel')
        //     ->table('customers') // Ensure this matches the external database table name
        //     ->where('cust_id', $customerId) // Ensure this matches the external database column
        //     ->get();

        // // $customer = Customer::where('cust_id', '==', $customerId)->get();
        // Log::info($customer);
        // return false;
        // return Customer::where('cust_id', '==', $customerId)->exists();
    }

    /**
     * Check if a customer exists in an external database.
     *
     * @param int $vehicleId
     * @return bool
     */
    public static function doesVehicleExistInExternalDb($vehicleId)
    {
        return DB::connection('mysql_non_laravel')
            ->table('vehicles') // Ensure this matches the external database table name
            ->where('vec_id', $vehicleId) // Ensure this matches the external database column
            ->exists();
    }
}
