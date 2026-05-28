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
        Schema::table('entity_diving_courses', function (Blueprint $table) {
            $table->foreignId('district_id')->nullable()->after('certification_system')->constrained('districts')->nullOnDelete();
            $table->string('location')->nullable()->after('district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entity_diving_courses', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropColumn(['district_id', 'location']);
        });
    }
};
