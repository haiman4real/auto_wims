<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'vehicles';

    protected $connection = 'mysql_non_laravel';

    // Define the primary key
    protected $primaryKey = 'vec_id';

    // Disable timestamps as the table does not use Laravel's created_at/updated_at columns
    public $timestamps = false;

    // Define the fillable fields
    protected $fillable = [
        'cust_id',
        'vec_body',
        'vec_year',
        'vec_make',
        'vec_model',
        'vec_vin',
        'vec_plate',
        'vec_view',
        'vec_reg_time',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }
}
