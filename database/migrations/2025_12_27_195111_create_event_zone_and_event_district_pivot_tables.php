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
        Schema::create('event_zone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('evt_events')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['event_id', 'zone_id']);
        });

        Schema::create('event_district', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('evt_events')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['event_id', 'district_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_district');
        Schema::dropIfExists('event_zone');
    }
};
