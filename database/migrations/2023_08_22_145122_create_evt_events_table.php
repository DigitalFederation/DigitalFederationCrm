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
        Schema::create('evt_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('event_type');
            $table->string('event_scope')->nullable();
            $table->string('featured_image')->nullable();
            $table->foreignId('sport_id')->nullable()->constrained('evt_sports');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_events');
    }
};
