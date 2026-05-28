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
        Schema::table('license_required_certifications', function (Blueprint $table) {
            // Make certification_id nullable to support certification level requirements
            $table->foreignId('certification_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_required_certifications', function (Blueprint $table) {
            // Remove nullable constraint from certification_id
            $table->foreignId('certification_id')->nullable(false)->change();
        });
    }
};
