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
        Schema::table('diving_log_freediving', function (Blueprint $table) {
            $table->dropColumn('static_details');
            $table->dropColumn('distance_disciplines_details');
            $table->integer('warm_ups')->nullable()->after('freedive_discipline');
            $table->integer('max_time')->nullable()->after('warm_ups');
            $table->integer('contraction_time')->nullable()->after('max_time');
            $table->integer('time')->nullable()->after('contraction_time');
            $table->integer('max_distance')->nullable()->after('time');
            $table->string('max_distance_unit', 20)->nullable()->after('max_distance');
            $table->integer('max_depth')->nullable()->after('max_distance_unit');
            $table->string('max_depth_unit', 20)->nullable()->after('max_depth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_log_freediving', function (Blueprint $table) {
            $table->string('static_details', 20)->nullable();
            $table->string('distance_disciplines_details', 20)->nullable();
            $table->dropColumn('warm_ups');
            $table->dropColumn('max_time');
            $table->dropColumn('contraction_time');
            $table->dropColumn('time');
            $table->dropColumn('max_distance');
            $table->dropColumn('max_distance_unit');
            $table->dropColumn('max_depth');
            $table->dropColumn('max_depth_unit');
        });
    }
};
