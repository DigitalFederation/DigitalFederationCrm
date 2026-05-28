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
        Schema::create('evt_staff_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_enrollment_id')->constrained('evt_staff_enrollment')->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained('evt_attributes')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['staff_enrollment_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_staff_attributes');
    }
};
