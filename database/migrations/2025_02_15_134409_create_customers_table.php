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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('cust_name');
            $table->string('cust_mobile');
            $table->string('cust_email')->unique()->nullable();
            $table->string('cust_address');
            $table->string('cust_lga');
            $table->string('cust_mode');
            $table->string('cust_type');
            $table->timestamp('cust_reg_time');
            $table->integer('cust_asset')->default(0);
            $table->string('cust_view')->default('visible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
