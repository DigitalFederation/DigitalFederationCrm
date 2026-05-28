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
        Schema::create('evt_antidoping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->integer('num_controls_planned');
            $table->date('date_updated');
            $table->integer('number_of_controls');

            // Foreign key constraint
            $table->foreign('competition_id')->references('id')->on('evt_competitions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_antidoping');
    }
};
