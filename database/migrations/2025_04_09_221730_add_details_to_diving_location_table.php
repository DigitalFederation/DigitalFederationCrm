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
            $table->string('depth')->nullable()->after('lng');
            $table->enum('water_type', ['Salt Water', 'Fresh Water'])->nullable()->after('depth');
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced'])->nullable()->after('water_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_location', function (Blueprint $table) {
            $table->dropColumn(['depth', 'water_type', 'level']);
        });
    }
};
