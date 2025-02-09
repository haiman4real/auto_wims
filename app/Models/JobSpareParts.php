<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobSpareParts extends Model
{
    use HasFactory;

    protected $table = 'wpdt_posts';  // WordPress posts table
    protected $connection = 'mysql_spareparts';  // Database connection
    protected $primaryKey = 'ID';      // Primary key
    public $timestamps = false;        // Disable timestamps

    /**
     * Get all products with their prices.
     *
     * @return array
     */
    public static function getSparePartsWithPrices()
    {
        return DB::connection('mysql_spareparts')->table('wpdt_posts as p')
            ->join('wpdt_postmeta as pm', 'p.ID', '=', 'pm.post_id')
            ->where('p.post_type', 'product')
            ->where('pm.meta_key', '_regular_price')
            ->select('p.ID', 'p.post_title', 'pm.meta_value as price')
            ->orderBy('p.post_title')
            ->get()
            ->toArray();
    }
}
