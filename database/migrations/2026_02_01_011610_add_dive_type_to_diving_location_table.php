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
        Schema::table('diving_location', function (Blueprint $table) {
            $table->json('dive_type')->nullable()->after('water_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_location', function (Blueprint $table) {
            $table->dropColumn('dive_type');
        });
    }
};
