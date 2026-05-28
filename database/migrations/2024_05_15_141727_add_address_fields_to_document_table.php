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
        Schema::table('document', function (Blueprint $table) {
            $table->string('customer_city')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_country')->nullable();
            $table->string('customer_postal_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document', function (Blueprint $table) {
            $table->dropColumn('customer_city');
            $table->dropColumn('customer_address');
            $table->dropColumn('customer_country');
            $table->dropColumn('customer_postal_code');
        });
    }
};
