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
            $table->unsignedInteger('competition_days')->nullable()->after('notes');
            $table->unsignedInteger('number_of_games')->nullable()->after('competition_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_referee_function_assignments', function (Blueprint $table) {
            $table->dropColumn(['competition_days', 'number_of_games']);
        });
    }
};
