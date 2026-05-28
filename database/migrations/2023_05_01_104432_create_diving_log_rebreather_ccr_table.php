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
        Schema::create('diving_log_rebreather_ccr', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diving_log_id')->index();
            $table->foreign('diving_log_id')->references('id')->on('diving_log')->onDelete('cascade');

            $table->unsignedInteger('runtime')->nullable();
            $table->unsignedInteger('ccr_total_deco_time')->nullable();

            $table->unsignedSmallInteger('depth')->nullable();
            $table->string('depth_unit', 10)->nullable();

            $table->unsignedInteger('bailout_sac')->nullable();
            $table->unsignedInteger('deco_sac')->nullable();
            $table->string('diluent_tank_type')->nullable(); // ENUM ['Steel', 'Aluminum']);

            $table->unsignedSmallInteger('diluent_tank_volume')->nullable();
            $table->string('diluent_tank_volume_unit', 10)->nullable();

            $table->unsignedTinyInteger('diluent_oxygen_percentage')->nullable();
            $table->unsignedTinyInteger('diluent_helium_percentage')->nullable();

            $table->unsignedSmallInteger('diluent_start_pressure')->nullable();
            $table->string('diluent_start_pressure_unit', 10)->nullable();
            $table->unsignedSmallInteger('diluent_end_pressure')->nullable();
            $table->string('diluent_end_pressure_unit', 10)->nullable();

            // Bailout Gas 01, 02, and 03
            for ($i = 1; $i <= 3; $i++) {
                $table->string("bailout_gas_{$i}_tank_type", 10)->nullable(); // Enum ['Steel', 'Aluminum', 'Other']);
                $table->unsignedSmallInteger("bailout_gas_{$i}_tank_volume")->nullable();
                $table->string("bailout_gas_{$i}_tank_volume_unit", 10)->nullable();
                $table->unsignedTinyInteger("bailout_gas_{$i}_oxygen_percentage")->nullable();
                $table->unsignedTinyInteger("bailout_gas_{$i}_helium_percentage")->nullable();
                $table->unsignedSmallInteger("bailout_gas_{$i}_start_pressure")->nullable();
                $table->string("bailout_gas_{$i}_start_pressure_unit", 10)->nullable();
                $table->unsignedSmallInteger("bailout_gas_{$i}_end_pressure")->nullable();
                $table->string("bailout_gas_{$i}_end_pressure_unit", 10)->nullable();
            }

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
        Schema::dropIfExists('diving_log_rebreather_ccr');
    }
};
