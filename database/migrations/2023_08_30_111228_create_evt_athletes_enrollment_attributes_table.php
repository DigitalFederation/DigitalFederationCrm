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
        Schema::create('evt_athletes_enrollment_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_enrollment_id')->constrained('evt_athletes_enrollment');
            $table->foreignId('attribute_id')->constrained('evt_attributes');
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_athletes_enrollment_attributes');
    }
};
