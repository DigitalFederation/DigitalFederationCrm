<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update request_type enum in insurances table
        DB::statement("ALTER TABLE insurances MODIFY COLUMN request_type ENUM('direct', 'entity_group', 'federation_facilitated') DEFAULT 'direct'");

        // Update request_type enum in affiliations table
        DB::statement("ALTER TABLE affiliations MODIFY COLUMN request_type ENUM('direct', 'entity_group', 'federation_facilitated') DEFAULT 'direct'");

        // Update request_type enum in member_subscriptions table
        DB::statement("ALTER TABLE member_subscriptions MODIFY COLUMN request_type ENUM('direct', 'entity_group', 'federation_facilitated') DEFAULT 'direct'");

        // Update request_type enum in license_attributed table
        DB::statement("ALTER TABLE license_attributed MODIFY COLUMN request_type ENUM('direct', 'entity_group', 'federation_facilitated') DEFAULT 'direct'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if any records use 'federation_facilitated' before reverting
        $insuranceCount = DB::table('insurances')->where('request_type', 'federation_facilitated')->count();
        $affiliationCount = DB::table('affiliations')->where('request_type', 'federation_facilitated')->count();
        $subscriptionCount = DB::table('member_subscriptions')->where('request_type', 'federation_facilitated')->count();
        $licenseCount = DB::table('license_attributed')->where('request_type', 'federation_facilitated')->count();

        if ($insuranceCount > 0 || $affiliationCount > 0 || $subscriptionCount > 0 || $licenseCount > 0) {
            throw new \Exception('Cannot rollback migration: There are records using "federation_facilitated" request type.');
        }

        // Revert request_type enum in insurances table
        DB::statement("ALTER TABLE insurances MODIFY COLUMN request_type ENUM('direct', 'entity_group') DEFAULT 'direct'");

        // Revert request_type enum in affiliations table
        DB::statement("ALTER TABLE affiliations MODIFY COLUMN request_type ENUM('direct', 'entity_group') DEFAULT 'direct'");

        // Revert request_type enum in member_subscriptions table
        DB::statement("ALTER TABLE member_subscriptions MODIFY COLUMN request_type ENUM('direct', 'entity_group') DEFAULT 'direct'");

        // Revert request_type enum in license_attributed table
        DB::statement("ALTER TABLE license_attributed MODIFY COLUMN request_type ENUM('direct', 'entity_group') DEFAULT 'direct'");
    }
};
