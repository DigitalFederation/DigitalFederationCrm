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
        Schema::create('shipping_zone_sub_zone', function (Blueprint $table) {
            $table->foreignId('zone_id')->constrained('shipping_zones')->onDelete('cascade');
            $table->foreignId('sub_zone_id')->constrained('shipping_sub_zones')->onDelete('cascade');
            $table->primary(['zone_id', 'sub_zone_id']); // Composite primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_zone_sub_zone');
    }
};
