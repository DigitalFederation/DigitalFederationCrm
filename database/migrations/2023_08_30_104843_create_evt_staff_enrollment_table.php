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
        Schema::create('evt_staff_enrollment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('evt_events');
            $table->foreignId('federation_id')->nullable()->constrained('federation'); // Federation to which the staff belongs
            $table->foreignUuid('individual_id')->nullable()->constrained('individual'); // Link to a user if they exist, but keep it nullable for non-system users
            $table->string('first_name');
            $table->string('last_name');
            $table->string('role'); // Role such as 'security', 'doctor', etc.
            $table->string('color_code')->nullable(); // Corresponding color for the role on the badge
            $table->string('duration'); // Example values; adjust accordingly
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_staff_enrollment');
    }
};
