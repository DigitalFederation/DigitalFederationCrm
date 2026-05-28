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
        Schema::create('evt_organizers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organizable_id');
            $table->string('organizable_type');
            $table->unsignedBigInteger('event_id');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('evt_events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_organizers');
    }
};
