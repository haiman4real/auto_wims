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
        Schema::connection('mysql_non_laravel')->table('vehicles', function (Blueprint $table) {
            $table->foreign('cust_id')->references('cust_id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_non_laravel')->table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['cust_id']); // Remove foreign key
        });
    }
};
