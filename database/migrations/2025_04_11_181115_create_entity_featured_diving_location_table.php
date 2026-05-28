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
        Schema::create('entity_featured_diving_location', function (Blueprint $table) {
            $table->foreignId('entity_id')->constrained('entity')->onDelete('cascade');
            $table->foreignId('diving_location_id')->constrained('diving_location')->onDelete('cascade');
            $table->primary(['entity_id', 'diving_location_id']); // Composite primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_featured_diving_location');
    }
};
