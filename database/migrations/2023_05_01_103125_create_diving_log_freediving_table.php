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
        Schema::create('diving_log_freediving', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diving_log_id')->index();
            $table->foreign('diving_log_id')->references('id')->on('diving_log')->onDelete('cascade');

            $table->string('entry', 20)->nullable(); // ENUM ['shore_beach', 'boat_dive', 'other']);
            $table->string('freedive_discipline', 20)->nullable(); // Enum DivingLogFreedive

            $table->string('static_details', 20)->nullable(); // ENUM
            $table->string('distance_disciplines_details', 20)->nullable(); // ENUM

            $table->string('equipment_suit', 100)->nullable();
            $table->string('equipment_mask', 100)->nullable();
            $table->string('equipment_fins', 100)->nullable();
            $table->string('equipment_dive_computer', 100)->nullable();
            $table->text('equipment_other')->nullable();
            $table->unsignedSmallInteger('equipment_weight')->nullable();
            $table->string('equipment_weight_unit', 10)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diving_log_freediving');
    }
};
