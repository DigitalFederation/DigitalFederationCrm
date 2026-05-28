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
        Schema::create('diving_log_diving', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diving_log_id')->index();
            $table->foreign('diving_log_id')->references('id')->on('diving_log')->onDelete('cascade');

            // Dive Details
            $table->string('entry', 50)->nullable(); // ENUM ['shore_beach', 'boat_dive', 'other']);
            $table->json('speciality_dive')->nullable(); // ENUM DivingLogSpecialityDiveEnum
            $table->integer('duration_minutes')->nullable();
            $table->integer('depth')->nullable();
            $table->string('depth_unit', 10)->nullable();
            $table->integer('nitrox_percentage')->nullable();
            $table->string('tank_type', 100)->nullable(); // ENUM ['steel', 'aluminum']
            $table->integer('tank_volume')->nullable();
            $table->string('tank_volume_unit', 10)->nullable();
            $table->integer('start_pressure')->nullable();
            $table->string('start_pressure_unit', 10)->nullable();
            $table->integer('end_pressure')->nullable();
            $table->string('end_pressure_unit', 10)->nullable();
            $table->integer('average_depth')->nullable();

            // Equipment
            $table->string('equipment_suit', 100)->nullable();
            $table->string('equipment_mask', 100)->nullable();
            $table->string('equipment_fins', 100)->nullable();
            $table->string('equipment_bcd_wing_sidemount', 100)->nullable();
            $table->string('equipment_first_stage', 100)->nullable();
            $table->string('equipment_second_stage', 100)->nullable();
            $table->string('equipment_dive_computer', 100)->nullable();
            $table->string('equipment_lights', 100)->nullable();
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
        Schema::dropIfExists('diving_log_diving');
    }
};
