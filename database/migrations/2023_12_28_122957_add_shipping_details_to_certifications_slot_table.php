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

            $table->unsignedBigInteger('shipping_zone_id')->nullable();
            $table->foreign('shipping_zone_id')->references('id')->on('shipping_zones')->onDelete('cascade');

            // Shipping price decimal
            $table->decimal('shipping_price', 10, 2)->nullable();

            // Shipping method id
            $table->unsignedBigInteger('shipping_method_id')->nullable();
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certifications_slot', function (Blueprint $table) {
            $table->dropForeign('certifications_slot_shipping_zone_id_foreign');
            $table->dropColumn('shipping_zone_id');
            $table->dropColumn('shipping_price');
            $table->dropForeign('certifications_slot_shipping_method_id_foreign');
            $table->dropColumn('shipping_method_id');
        });
    }
};
