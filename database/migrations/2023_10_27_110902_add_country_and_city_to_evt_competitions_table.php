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
            $table->integer('venue_country_id')->nullable()->after('venue_address')->constrained('country');
            $table->string('venue_city')->nullable()->after('venue_country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('country_id');
            $table->dropColumn('country_id');
            $table->dropColumn('venue_city');
        });
    }
};
