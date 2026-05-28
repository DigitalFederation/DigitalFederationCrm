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
        // package_affiliation
        if (! Schema::hasTable('package_affiliation')) {
            Schema::create('package_affiliation', function (Blueprint $table) {
                $table->foreignId('package_id')->constrained('membership_packages')->onDelete('cascade');
                $table->foreignId('affiliation_id')->constrained('affiliation_plans')->onDelete('cascade');
                $table->primary(['package_id', 'affiliation_id']);
            });
        }

        // package_license
        if (! Schema::hasTable('package_license')) {
            Schema::create('package_license', function (Blueprint $table) {
                $table->foreignId('package_id')->constrained('membership_packages')->onDelete('cascade');
                $table->foreignId('license_id')->constrained('license')->onDelete('cascade');
                $table->primary(['package_id', 'license_id']);
            });
        }

        // package_federation
        if (! Schema::hasTable('package_federation')) {
            Schema::create('package_federation', function (Blueprint $table) {
                $table->id();
                $table->foreignId('package_id')->constrained('membership_packages')->onDelete('cascade');
                $table->foreignId('federation_id')->constrained('federation')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['package_id', 'federation_id']);
            });
        }

        // package_pricings (stub: adjust as needed)
        if (! Schema::hasTable('package_pricings')) {
            Schema::create('package_pricings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('package_id')->constrained('membership_packages')->onDelete('cascade');
                $table->decimal('price', 10, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('package_pricings');
        Schema::dropIfExists('package_federation');
        Schema::dropIfExists('package_license');
        Schema::dropIfExists('package_affiliation');
    }
};
