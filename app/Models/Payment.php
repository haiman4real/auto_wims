<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //

    protected $table = 'service_payments';
    protected $connection = 'mysql_non_laravel';

    protected $fillable = [
        'service_job_id',
        'invoice',
        'total_amount',
        'amount_paid',
        'status',
        'transaction_id',
        'transaction_details',
        'response_data',
        'metadata',
    ];

    protected $casts = [
        'transaction_details' => 'array',
        'response_data'       => 'array',
        'metadata'            => 'array',
    ];

    public function serviceJob()
    {
        return $this->belongsTo(ServiceJobs::class);
    }
}
