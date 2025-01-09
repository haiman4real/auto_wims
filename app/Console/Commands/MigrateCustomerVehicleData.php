<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateCustomerVehicleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-customer-vehicle-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from customers_vehicles to vehicles table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data migration from customers_vehicles to vehicles table.');

        // Get all mappings from customers_vehicles
        $mappings = DB::connection('mysql_non_laravel')->table('customers_vehicles')->get();

        foreach ($mappings as $mapping) {
            DB::connection('mysql_non_laravel')->table('vehicles')
                ->where('vec_id', $mapping->vec_id) // Match vehicle ID
                ->update(['cust_id' => $mapping->cust_id]); // Update with customer ID
        }

        $this->info('Data migration completed successfully.');

        // Optional: Drop the `customers_vehicles` table
        if ($this->confirm('Do you want to drop the customers_vehicles table?')) {
            DB::connection('mysql_non_laravel')->statement('DROP TABLE IF EXISTS customers_vehicles');
            $this->info('customers_vehicles table dropped.');
        }

        return 0;
    }
}
