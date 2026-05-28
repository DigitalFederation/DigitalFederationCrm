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

        Schema::create('package_affiliation', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained('membership_packages')->onDelete('cascade');
            $table->foreignId('affiliation_id')->constrained('affiliation_plans')->onDelete('cascade');
            $table->primary(['package_id', 'affiliation_id']);
        });

        Schema::create('package_license', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained('membership_packages')->onDelete('cascade');
            $table->foreignId('license_id')->constrained('license')->onDelete('cascade');
            $table->primary(['package_id', 'license_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_license');
        Schema::dropIfExists('package_affiliation');
    }
};
