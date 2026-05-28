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
        // Event roles table (Technical Delegate, Chief Judge, Competition Director)
        Schema::create('evt_event_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->char('individual_id', 36);
            $table->enum('role', ['technical_delegate', 'chief_judge', 'competition_director']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('evt_events')->onDelete('cascade');
            $table->foreign('individual_id')->references('id')->on('individual')->onDelete('cascade');

            // Ensure one person can't have multiple roles in same event
            $table->unique(['event_id', 'individual_id']);
            // Ensure each role is only assigned once per event
            $table->unique(['event_id', 'role']);
        });

        // Referee functions (sport-specific functions for referees)
        Schema::create('evt_referee_functions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sport_id');
            $table->string('function_name');
            $table->string('function_code', 50)->nullable();
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('sport_id')->references('id')->on('evt_sports')->onDelete('cascade');
            $table->index(['sport_id', 'is_active']);
        });

        // Referee function assignments (post-event record keeping)
        Schema::create('evt_referee_function_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('referee_enrollment_id');
            $table->unsignedBigInteger('referee_function_id')->nullable();
            $table->text('function_text')->nullable(); // For custom/text-based functions
            $table->char('assigned_by', 36); // chief_judge individual_id
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('evt_events')->onDelete('cascade');
            $table->foreign('referee_enrollment_id')->references('id')->on('evt_referees_enrollment')->onDelete('cascade');
            $table->foreign('referee_function_id')->references('id')->on('evt_referee_functions')->onDelete('set null');
            $table->foreign('assigned_by')->references('id')->on('individual');

            $table->index(['event_id', 'referee_enrollment_id'], 'idx_event_referee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_referee_function_assignments');
        Schema::dropIfExists('evt_referee_functions');
        Schema::dropIfExists('evt_event_roles');
    }
};
