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
        Schema::create('evt_referees_enrollment_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referee_enrollment_id')->constrained('evt_referees_enrollment')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('evt_attributes')->onDelete('cascade');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['referee_enrollment_id', 'attribute_id'], 'unique_referee_attribute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_referees_enrollment_attributes');
    }
};
