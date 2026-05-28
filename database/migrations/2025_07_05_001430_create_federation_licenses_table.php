<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates pivot table for many-to-many relationship between federations and licenses.
     * This allows federations to control which specific licenses their member entities can request.
     */
    public function up(): void
    {
        Schema::create('federation_licenses', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('federation_id')
                ->constrained('federation')
                ->cascadeOnDelete()
                ->comment('Federation that can offer this license');

            $table->foreignId('license_id')
                ->constrained('license')
                ->cascadeOnDelete()
                ->comment('License that the federation can offer');

            $table->timestamps();

            // Indexes for performance
            $table->unique(['federation_id', 'license_id'], 'unique_federation_license');
            $table->index('federation_id', 'idx_federation_id');
            $table->index('license_id', 'idx_license_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('federation_licenses');
    }
};
