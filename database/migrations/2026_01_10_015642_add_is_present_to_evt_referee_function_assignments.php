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
        Schema::table('evt_referee_function_assignments', function (Blueprint $table) {
            $table->boolean('is_present')->default(true)->after('referee_enrollment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_referee_function_assignments', function (Blueprint $table) {
            $table->dropColumn('is_present');
        });
    }
};
