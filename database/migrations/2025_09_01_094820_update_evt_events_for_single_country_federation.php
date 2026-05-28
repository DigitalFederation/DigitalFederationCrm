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
            // Add district_id for venue location
            $table->unsignedBigInteger('venue_district_id')->nullable()->after('venue_city');
            $table->foreign('venue_district_id')->references('id')->on('districts')->onDelete('set null');

            // We'll keep venue_country_id for now but make it nullable for backwards compatibility
            // Can be removed in a future migration after data migration
            $table->unsignedBigInteger('venue_country_id')->nullable()->change();
        });

        // Update evt_event_geographic to support zones and districts instead of geo_zones and countries
        Schema::table('evt_event_geographic', function (Blueprint $table) {
            // Add new columns for zones and districts
            $table->unsignedBigInteger('zone_id')->nullable()->after('event_id');
            $table->unsignedBigInteger('district_id')->nullable()->after('zone_id');

            // Add foreign keys
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');

            // Make old columns nullable for backwards compatibility
            if (Schema::hasColumn('evt_event_geographic', 'geo_zone_id')) {
                $table->unsignedBigInteger('geo_zone_id')->nullable()->change();
            }
            if (Schema::hasColumn('evt_event_geographic', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_events', function (Blueprint $table) {
            $table->dropForeign(['venue_district_id']);
            $table->dropColumn('venue_district_id');

            // Restore venue_country_id to not nullable if it was before
            $table->unsignedBigInteger('venue_country_id')->nullable(false)->change();
        });

        Schema::table('evt_event_geographic', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['zone_id', 'district_id']);

            // Restore old columns to not nullable if they were before
            if (Schema::hasColumn('evt_event_geographic', 'geo_zone_id')) {
                $table->unsignedBigInteger('geo_zone_id')->nullable(false)->change();
            }
            if (Schema::hasColumn('evt_event_geographic', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable(false)->change();
            }
        });
    }
};
