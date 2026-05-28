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
        Schema::create('evt_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users'); // Assuming standard users table
            $table->foreignId('event_id')->constrained('evt_events');
            $table->uuid('enrollable_id')->nullable();
            $table->string('enrollable_type')->nullable();
            $table->timestamps();
        });

        // Adding indexes for polymorphic relationship
        Schema::table('evt_enrollments', function (Blueprint $table) {
            $table->index(['enrollable_id', 'enrollable_type']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_enrollments');
    }
};
