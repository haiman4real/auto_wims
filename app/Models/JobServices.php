<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobServices extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $connection = 'mysql_non_laravel';
    protected $primaryKey = 'serv_id';
    public $timestamps = false; // Set this if the table does not use timestamps
    protected $fillable = [
        'serv_name', 'serv_cat', 'serv_duration', 'serv_amount', 'serv_status'
    ];


}
