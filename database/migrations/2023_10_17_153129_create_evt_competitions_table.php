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
        Schema::create('evt_competitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->integer('year')->nullable();
            $table->string('month')->nullable();
            $table->string('number')->nullable();
            $table->integer('rounds_total')->nullable();
            $table->string('competition_type');
            $table->string('cat_age')->nullable();
            $table->string('cat_competition')->nullable();
            $table->string('environment')->nullable();
            $table->string('full_name')->nullable();
            $table->string('status_class')->nullable();
            $table->string('venue')->nullable();
            $table->string('venue_address')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('evt_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_competitions');
    }
};
