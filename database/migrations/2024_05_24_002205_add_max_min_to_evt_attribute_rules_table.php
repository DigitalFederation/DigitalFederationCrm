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
        Schema::table('evt_attribute_rules', function (Blueprint $table) {
            $table->integer('max_value')->nullable()->after('is_validation')->comment('Maximum allowed value for the attribute');
            $table->integer('min_value')->nullable()->after('is_validation')->comment('Minimum allowed value for the attribute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_attribute_rules', function (Blueprint $table) {
            $table->dropColumn('max_value');
            $table->dropColumn('min_value');
        });
    }
};
