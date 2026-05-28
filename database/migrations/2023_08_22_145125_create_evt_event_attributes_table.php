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
        Schema::create('evt_event_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('evt_events');
            // Assume there's an evt_attribute_templates table.
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
        Schema::dropIfExists('evt_event_attributes');
    }
};
