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
        Schema::create('diving_log_extended_range', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diving_log_id')->index();

            $table->unsignedSmallInteger('total_runtime')->nullable();
            $table->unsignedSmallInteger('total_deco_time')->nullable();
            $table->integer('depth')->nullable();
            $table->string('depth_unit', 10)->nullable();
            $table->string('configuration', 50)->nullable(); // ENUM('Single Cylinder', 'Twinset', 'Sidemount Cylinders')

            $table->integer('sac_bottom_sac')->nullable();
            $table->integer('sac_sac')->nullable();
            $table->integer('sac_deco_sac')->nullable();

            $table->string('details_si_before', 20)->nullable();
            $table->string('details_gf_set', 20)->nullable();
            $table->string('details_gradient_factor_end', 20)->nullable();
            $table->string('details_cns_start', 20)->nullable();
            $table->string('details_cns_end', 20)->nullable();
            $table->string('details_otu_start', 20)->nullable();
            $table->string('details_otu_end', 20)->nullable();

            // Repeated for Back Gas, Deco Gas 1, Deco Gas 2, Deco Gas 3
            for ($i = 1; $i <= 4; $i++) {
                $prefix = $i === 1 ? 'back_gas' : 'deco_gas_'.$i - 1;

                $table->unsignedSmallInteger("{$prefix}_tank_volume")->nullable();
                $table->string("{$prefix}_tank_volume_unit", 10)->nullable();
                $table->unsignedSmallInteger("{$prefix}_start_pressure")->nullable();
                $table->string("{$prefix}_start_pressure_unit", 10)->nullable();
                $table->unsignedSmallInteger("{$prefix}_end_pressure")->nullable();
                $table->string("{$prefix}_end_pressure_unit", 10)->nullable();
                $table->string("{$prefix}_tank_type", 50)->nullable(); // ENUM('Aluminium', 'Steel', 'Other')
                $table->unsignedTinyInteger("{$prefix}_oxygen_percentage")->nullable();
                $table->unsignedTinyInteger("{$prefix}_helium_percentage")->nullable();
            }

            $table->foreign('diving_log_id')->references('id')->on('diving_log')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diving_log_extended_range');
    }
};
