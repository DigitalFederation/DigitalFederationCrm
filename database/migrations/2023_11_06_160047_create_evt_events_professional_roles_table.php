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
        Schema::create('evt_events_professional_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained('evt_events');
            $table->foreignId('professional_role_id')->index()->constrained('professional_roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_events_professional_roles');
    }
};
