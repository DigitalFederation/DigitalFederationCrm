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
        Schema::create('evt_discipline_sport_age_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('sport_age_group_id');
            $table->timestamps();

            $table->foreign('discipline_id')
                ->references('id')
                ->on('evt_disciplines')
                ->onDelete('cascade');

            $table->foreign('sport_age_group_id')
                ->references('id')
                ->on('evt_sport_age_groups')
                ->onDelete('cascade');

            $table->unique(['discipline_id', 'sport_age_group_id'], 'discipline_age_group_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_discipline_sport_age_groups');
    }
};
