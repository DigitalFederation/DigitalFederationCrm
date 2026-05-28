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
        Schema::create('diving_log_rebreather_scr', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diving_log_id')->index();
            $table->foreign('diving_log_id')->references('id')->on('diving_log')->onDelete('cascade');

            $table->unsignedInteger('runtime')->nullable();
            $table->unsignedInteger('scr_total_deco_time')->nullable();

            $table->unsignedSmallInteger('depth')->nullable();
            $table->string('depth_unit', 10)->nullable();

            $table->unsignedSmallInteger('weight')->nullable();
            $table->string('weight_unit', 10)->nullable();

            $table->unsignedInteger('bailout_sac')->nullable();
            $table->unsignedInteger('deco_sac')->nullable();
            $table->unsignedSmallInteger('tank_volume')->nullable();
            $table->string('tank_volume_unit', 10)->nullable();

            $table->unsignedTinyInteger('oxygen_percentage')->nullable();

            $table->unsignedInteger('setpoint')->nullable();

            $table->unsignedSmallInteger('start_pressure')->nullable();
            $table->string('start_pressure_unit', 10)->nullable();
            $table->unsignedSmallInteger('end_pressure')->nullable();
            $table->string('end_pressure_unit', 10)->nullable();

            $table->unsignedSmallInteger('deco_tank_volume')->nullable();
            $table->string('deco_tank_volume_unit', 10)->nullable();

            $table->unsignedTinyInteger('deco_oxygen_percentage')->nullable();
            $table->unsignedInteger('deco_setpoint')->nullable();

            $table->unsignedSmallInteger('deco_start_pressure')->nullable();
            $table->string('deco_start_pressure_unit', 10)->nullable();

            $table->unsignedSmallInteger('deco_end_pressure')->nullable();
            $table->string('deco_end_pressure_unit', 10)->nullable();

            $table->string('entry', 10)->nullable(); // ENUM ['Shore / Beach', 'Boat Dive', 'Other']);
            $table->string('water_type', 10)->nullable(); // ENUM ['Salt Water', 'Fresh Water']);
            $table->string('current', 20)->nullable(); // ENUM ['No Current', 'Light Current', 'Strong Current', 'Ripping Current']);
            $table->string('surface', 20)->nullable(); // ENUM ['Calm', 'Moving', 'Storm']);

            $table->string('equipment_suit', 100)->nullable();
            $table->string('equipment_mask', 100)->nullable();
            $table->string('equipment_fins', 100)->nullable();
            $table->string('equipment_bcd_wing_sidemount', 100)->nullable();
            $table->string('equipment_rebreather_unit', 100)->nullable();
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
        Schema::dropIfExists('diving_log_rebreather_scr');
    }
};
