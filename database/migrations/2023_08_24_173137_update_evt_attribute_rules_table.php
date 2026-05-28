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
            $table->boolean('is_validation')->default(false); // Adding is_validation column
            $table->string('comparison_value')->nullable(); // Adding comparison_value column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_attribute_rules', function (Blueprint $table) {
            $table->dropColumn('is_validation'); // Removing is_validation column
            $table->dropColumn('comparison_value'); // Removing comparison_value column
        });
    }
};
