<?php

use Domain\DivingLogs\States\DraftDivingLogState;
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
        Schema::create('diving_log', function (Blueprint $table) {
            $table->id();
            $table->char('individual_id', 36)->index();
            $table->string('dive_type', 50); // ENUM ['diving', 'freediving', 'extended_range', 'rebreather_ccr', 'rebreather_scr'];
            $table->string('category', 50)->nullable(); // ENUM ['recreational', 'training_course_dive', 'scientific', 'work'];
            $table->unsignedBigInteger('buddy_id')->nullable();
            $table->unsignedBigInteger('diving_location_id')->nullable();
            $table->dateTime('date_and_time');
            $table->integer('dive_site_score')->nullable();
            $table->string('status_class')->default(DraftDivingLogState::class);
            $table->string('environment_entry')->nullable();
            $table->string('environment_water_type')->nullable();
            $table->string('environment_current')->nullable();
            $table->string('environment_surface')->nullable();
            $table->unsignedSmallInteger('environment_water_temperature')->nullable();
            $table->string('environment_water_temperature_unit', 10)->nullable();
            $table->unsignedSmallInteger('environment_air_temperature')->nullable();
            $table->string('environment_air_temperature_unit', 10)->nullable();
            $table->unsignedSmallInteger('environment_water_visibility')->nullable();
            $table->string('environment_water_visibility_unit', 10)->nullable();
            $table->text('wildlife')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->foreign('individual_id')->references('id')->on('individual')->onDelete('cascade');
            $table->foreign('buddy_id')->references('id')->on('diving_buddies')->onDelete('cascade');
            // $table->foreign('diving_location_id')->references('id')->on('dive_locations')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diving_logs');
    }
};
