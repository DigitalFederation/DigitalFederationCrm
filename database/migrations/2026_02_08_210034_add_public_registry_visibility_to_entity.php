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
        Schema::table('entity', function (Blueprint $table) {
            $table->boolean('visible_in_club_registry')->default(true);
            $table->boolean('visible_in_diving_service_provider_registry')->default(true);
            $table->boolean('visible_in_map')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entity', function (Blueprint $table) {
            $table->dropColumn([
                'visible_in_club_registry',
                'visible_in_diving_service_provider_registry',
                'visible_in_map',
            ]);
        });
    }
};
