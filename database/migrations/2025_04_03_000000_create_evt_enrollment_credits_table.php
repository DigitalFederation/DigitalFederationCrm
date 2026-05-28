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
        Schema::create('evt_enrollment_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->morphs('enrollable');
            $table->string('role_type');
            $table->integer('available_slots')->default(0);
            $table->decimal('monetary_value', 10, 2)->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('evt_events');

            // Use a custom index name to avoid the length limitation
            $table->index(['event_id', 'enrollable_id', 'enrollable_type', 'role_type'], 'evt_credits_composite_index');
        });

        // Add credits_applied column to enrollments table
        Schema::table('evt_enrollments', function (Blueprint $table) {
            $table->json('credits_applied')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the added column first
        Schema::table('evt_enrollments', function (Blueprint $table) {
            $table->dropColumn('credits_applied');
        });

        // Drop the credits table
        Schema::dropIfExists('evt_enrollment_credits');
    }
};
