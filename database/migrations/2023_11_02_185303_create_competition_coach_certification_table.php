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
        Schema::create('evt_competition_coach_certification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certification_id');
            $table->foreignId('competition_id')->constrained('evt_competitions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_competition_coach_certification');
    }
};
