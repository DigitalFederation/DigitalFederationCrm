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
        Schema::table('certifications_slot', function (Blueprint $table) {
            $table->string('shipping_address')->nullable();
            $table->string('shipping_location')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country_zone')->nullable();
            $table->string('shipping_country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certifications_slot', function (Blueprint $table) {
            $table->dropColumn('shipping_address');
            $table->dropColumn('shipping_location');
            $table->dropColumn('shipping_postal_code');
            $table->dropColumn('shipping_country_zone');
            $table->dropColumn('shipping_country');
        });
    }
};
