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
        Schema::table('certification', function (Blueprint $table) {
            $table->integer('minimum_age')->nullable()->after('certification_category');
            $table->integer('confined_water_sessions')->nullable()->after('minimum_age');
            $table->integer('open_water_sessions')->nullable()->after('confined_water_sessions');
            $table->integer('theoretical_sessions')->nullable()->after('open_water_sessions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification', function (Blueprint $table) {
            $table->dropColumn([
                'minimum_age',
                'confined_water_sessions',
                'open_water_sessions',
                'theoretical_sessions',
            ]);
        });
    }
};
