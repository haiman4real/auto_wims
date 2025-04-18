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
        Schema::create('allowed_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->unique(); // IP address
            $table->string('environment')->default('production'); // Specify environment (e.g., local, production)
            $table->string('organization')->nullable(); // Organization associated with the IP
            $table->enum('status', ['allowed', 'banned', 'pending'])->default('pending'); // Status of the IP
            $table->json('metadata')->nullable(); // Store any additional metadata
            $table->timestamps(); // Timestamps for auditing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowed_ips');
    }
};
