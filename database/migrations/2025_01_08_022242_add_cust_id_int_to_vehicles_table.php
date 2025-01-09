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
            if (!Schema::connection('mysql_non_laravel')->hasColumn('vehicles', 'cust_id')) {
                $table->integer('cust_id')->default(1)->after('vec_id'); // Add column with a default value
            }
            $table->integer('cust_id')->default(1)->change(); // Make cust_id unsigned and set default
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_non_laravel')->table('vehicles', function (Blueprint $table) {
            $table->integer('cust_id')->change(); // Make cust_id unsigned // Add cust_id column
        });
    }
};
