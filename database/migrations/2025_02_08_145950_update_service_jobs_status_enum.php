<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Specify the database connection.
     */
    protected $connection = 'mysql_non_laravel';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Update existing values before modifying ENUM
        DB::connection($this->connection)->statement("UPDATE service_jobs SET status = 'in progress' WHERE status = 'in_progress'");

        // Step 2: Modify the ENUM column safely
        DB::connection($this->connection)->statement("ALTER TABLE service_jobs MODIFY COLUMN status ENUM('pending', 'in progress', 'estimate generated', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert changes: Update 'in progress' back to 'in_progress'
        DB::connection($this->connection)->statement("UPDATE service_jobs SET status = 'in_progress' WHERE status = 'in progress'");

        // Revert ENUM modification
        DB::connection($this->connection)->statement("ALTER TABLE service_jobs MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
