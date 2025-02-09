<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql_non_laravel')->create('service_jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('customerId'); // Foreign key to customers
            $table->integer('vehicleId');  // Foreign key to vehicles

            // Foreign key constraints
            $table->foreign('customerId')->references('cust_id')->on('customers')->onDelete('cascade');
            $table->foreign('vehicleId')->references('vec_id')->on('vehicles')->onDelete('cascade');
            $table->string('order_number')->unique(); // Unique job order number
            $table->date('job_start_date'); // Job start date
            $table->text('description')->nullable(); // Service description/complaints
            $table->json('other_details')->nullable(); // JSON for additional form fields
            $table->json('work_notes')->nullable(); // JSON for work notes fields
            $table->json('workflow')->nullable(); // JSON to track job workflow stages
            $table->enum('status', ['pending', 'in progress', 'estimate generated', 'completed', 'cancelled'])->default('pending'); // Job status
            $table->json('estimated_jobs')->nullable(); // JSON for spare parts and service charges
            $table->decimal('total_cost', 10, 2)->nullable(); // Total cost
            $table->json('comments')->nullable(); // JSON for comments
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_jobs');
    }
};
