<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection('mysql_non_laravel')->table('customers', function (Blueprint $table) {
            $table->string('cust_email')->nullable()->change(); // Make the email column nullable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_non_laravel')->table('customers', function (Blueprint $table) {
            $table->string('cust_email')->nullable(false)->change(); // Revert the column to not nullable
        });
    }
};
