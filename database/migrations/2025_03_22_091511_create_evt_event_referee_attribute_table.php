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
        Schema::create('evt_event_referee_attribute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('evt_events')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('evt_attributes')->onDelete('cascade');
            $table->timestamps();

            // Add a unique constraint to prevent duplicate entries
            $table->unique(['event_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_event_referee_attribute');
    }
};
