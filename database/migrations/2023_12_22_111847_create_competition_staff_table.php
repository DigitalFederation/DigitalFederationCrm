<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evt_competition_staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->uuid('individual_id');
            $table->timestamps();

            $table->foreign('competition_id')->references('id')->on('evt_competitions')->onDelete('cascade');
            $table->foreign('individual_id')->references('id')->on('individual')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evt_competition_staff');
    }
};
