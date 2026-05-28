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
        Schema::create('shipping_weights', function (Blueprint $table) {
            $table->id();
            $table->string('range')->nullable();
            $table->decimal('minimum_weight', 10, 2)->default(0);
            $table->decimal('maximum_weight', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_weights');
    }
};
