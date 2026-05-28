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
        Schema::create('evt_chief_judge_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->char('submitted_by', 36);
            $table->text('technical_considerations')->nullable();
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
        Schema::dropIfExists('evt_chief_judge_reports');
    }
};
