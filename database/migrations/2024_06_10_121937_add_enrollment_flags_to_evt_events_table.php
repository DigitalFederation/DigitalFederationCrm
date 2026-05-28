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
        Schema::table('evt_events', function (Blueprint $table) {
            $table->boolean('allow_coach_enrollment')->default(true);
            $table->boolean('allow_referee_enrollment')->default(true);
            $table->boolean('allow_individual_enrollment')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_events', function (Blueprint $table) {
            $table->dropColumn('allow_coach_enrollment');
            $table->dropColumn('allow_referee_enrollment');
            $table->dropColumn('allow_individual_enrollment');
        });
    }
};
