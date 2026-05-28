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
        Schema::table('evt_disciplines', function (Blueprint $table) {
            $table->dropColumn(['interval_date_birth_start', 'interval_date_birth_end']);
            $table->foreignId('sport_age_group_id')->nullable()->constrained('evt_sport_age_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_disciplines', function (Blueprint $table) {
            $table->date('interval_date_birth_start');
            $table->date('interval_date_birth_end');
            $table->dropForeign(['sport_age_group_id']);
            $table->dropColumn('sport_age_group_id');
        });
    }
};
