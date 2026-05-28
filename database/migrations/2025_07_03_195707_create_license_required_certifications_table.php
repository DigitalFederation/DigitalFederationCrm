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
        Schema::create('license_required_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained('license')->onDelete('cascade');
            $table->foreignId('certification_id')->constrained('certification')->onDelete('cascade');
            $table->string('requester_type')->nullable(); // Individual, Entity, Federation, or null for all
            $table->timestamps();

            // Add unique constraint to prevent duplicates
            $table->unique(['license_id', 'certification_id', 'requester_type'], 'lic_req_cert_unique');

            // Add indexes for performance
            $table->index(['license_id', 'requester_type']);
            $table->index('certification_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_required_certifications');
    }
};
