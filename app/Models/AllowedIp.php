<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllowedIp extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'environment',
        'organization',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array', // Automatically cast metadata to/from JSON
    ];
}
