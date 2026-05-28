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
        Schema::create('evt_referees_enrollment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('evt_enrollments');
            $table->foreignId('federation_id')->nullable()->constrained('federation');
            $table->foreignId('event_id')->constrained('evt_events');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_referees_enrollment');
    }
};
