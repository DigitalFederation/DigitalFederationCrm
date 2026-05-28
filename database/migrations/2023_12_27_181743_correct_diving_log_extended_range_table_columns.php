<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('diving_log_extended_range', function (Blueprint $table) {
            // Add missing columns for Deco Gas
            for ($i = 1; $i <= 3; $i++) {
                $prefix = 'deco_gas_' . $i;

                if (! Schema::hasColumn('diving_log_extended_range', "{$prefix}_tank_volume")) {
                    $table->unsignedSmallInteger("{$prefix}_tank_volume")->nullable();
                }
                if (! Schema::hasColumn('diving_log_extended_range', "{$prefix}_tank_volume_unit")) {
                    $table->string("{$prefix}_tank_volume_unit", 10)->nullable();
                }
                if (! Schema::hasColumn('diving_log_extended_range', "{$prefix}_start_pressure")) {
                    $table->unsignedSmallInteger("{$prefix}_start_pressure")->nullable();
                }
                if (! Schema::hasColumn('diving_log_extended_range', "{$prefix}_start_pressure_unit")) {
                    $table->string("{$prefix}_start_pressure_unit", 10)->nullable();
                }
                if (! Schema::hasColumn('diving_log_extended_range', "{$prefix}_end_pressure")) {
                    $table->unsignedSmallInteger("{$prefix}_end_pressure")->nullable();
                }
                if (! Schema::hasColumn('diving_log_extended_range', "{$prefix}_end_pressure_unit")) {
                    $table->string("{$prefix}_end_pressure_unit", 10)->nullable();
                }
                if (! Schema::hasColumn('diving_log_extended_range', "{$prefix}_tank_type")) {
                    $table->string("{$prefix}_tank_type", 50)->nullable();
                }
                if (! Schema::hasColumn('diving_log_extended_range', "{$prefix}_oxygen_percentage")) {
                    $table->unsignedTinyInteger("{$prefix}_oxygen_percentage")->nullable();
                }
                if (! Schema::hasColumn('diving_log_extended_range', "{$prefix}_helium_percentage")) {
                    $table->unsignedTinyInteger("{$prefix}_helium_percentage")->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_log_extended_range', function (Blueprint $table) {
            for ($i = 3; $i >= 1; $i--) {
                // Reverting the names for Deco Gas columns
                $oldPrefix = 'deco_gas_' . $i;
                $newPrefix = 'deco_gas_' . ($i + 1);

                $table->renameColumn("{$newPrefix}_tank_volume", "{$oldPrefix}_tank_volume");
                $table->renameColumn("{$newPrefix}_tank_volume_unit", "{$oldPrefix}_tank_volume_unit");
                $table->renameColumn("{$newPrefix}_start_pressure", "{$oldPrefix}_start_pressure");
                $table->renameColumn("{$newPrefix}_start_pressure_unit", "{$oldPrefix}_start_pressure_unit");
                $table->renameColumn("{$newPrefix}_end_pressure", "{$oldPrefix}_end_pressure");
                $table->renameColumn("{$newPrefix}_end_pressure_unit", "{$oldPrefix}_end_pressure_unit");
                $table->renameColumn("{$newPrefix}_tank_type", "{$oldPrefix}_tank_type");
                $table->renameColumn("{$newPrefix}_oxygen_percentage", "{$oldPrefix}_oxygen_percentage");
                $table->renameColumn("{$newPrefix}_helium_percentage", "{$oldPrefix}_helium_percentage");
            }
        });
    }
};
