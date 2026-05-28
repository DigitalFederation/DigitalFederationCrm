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
        Schema::create('evt_competition_discipline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->unsignedBigInteger('discipline_id');

            $table->unique(['competition_id', 'discipline_id']); // Ensure unique combination

            $table->foreign('competition_id')
                ->references('id')
                ->on('evt_competitions')
                ->onDelete('cascade');

            $table->foreign('discipline_id')
                ->references('id')
                ->on('evt_disciplines')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_competition_discipline');
    }
};
