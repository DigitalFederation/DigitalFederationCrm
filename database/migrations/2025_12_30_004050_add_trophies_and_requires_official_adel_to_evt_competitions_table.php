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
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->unsignedInteger('trophies_first')->nullable()->after('medals_bronze');
            $table->unsignedInteger('trophies_second')->nullable()->after('trophies_first');
            $table->unsignedInteger('trophies_third')->nullable()->after('trophies_second');
            $table->boolean('requires_official_adel')->default(false)->after('requires_referee_adel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropColumn([
                'trophies_first',
                'trophies_second',
                'trophies_third',
                'requires_official_adel',
            ]);
        });
    }
};
