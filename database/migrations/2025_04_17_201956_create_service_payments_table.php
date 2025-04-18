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
        Schema::connection('mysql_non_laravel')->create('service_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_job_id')
                  ->constrained('service_jobs')
                  ->onDelete('cascade');
            $table->string('invoice')->unique();
            $table->decimal('total_amount', 12, 2);   // invoice total
            $table->decimal('amount_paid', 12, 2)     // how much customer has paid
                  ->default(0);
            $table->enum('status', ['pending','partial','paid'])
                  ->default('pending');
            $table->uuid('transaction_id')            // your internal transaction UUID
                  ->nullable();
            // JSON columns for full request/response/metadata
            $table->json('transaction_details')->nullable();
            $table->json('response_data')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_payments');
    }
};
