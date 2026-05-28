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
        Schema::create('evt_sport_age_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained('evt_sports');
            $table->string('title');
            $table->date('birthday_start');
            $table->date('birthday_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_sport_age_groups');
    }
};
