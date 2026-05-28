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
        Schema::create('evt_technical_delegate_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->char('submitted_by', 36);
            $table->text('participants_withdrawals')->nullable();
            $table->text('incidents_occurrences')->nullable();
            $table->text('officials_performance')->nullable();
            $table->text('facilities_evaluation')->nullable();
            $table->text('safety_first_aid')->nullable();
            $table->text('anti_doping_control')->nullable();
            $table->text('sports_protests')->nullable();
            $table->text('observations_recommendations')->nullable();
            $table->boolean('is_submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('evt_events')->onDelete('cascade');
            $table->foreign('submitted_by')->references('id')->on('individual')->onDelete('cascade');
            $table->unique('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_technical_delegate_reports');
    }
};
