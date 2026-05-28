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
        Schema::create('evt_coaches_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_enrollment_id')->constrained('evt_coaches_enrollment')->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained('evt_attributes')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_coaches_attributes');
    }
};
