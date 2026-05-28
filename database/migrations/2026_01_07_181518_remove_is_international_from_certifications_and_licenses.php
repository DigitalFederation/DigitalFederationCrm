<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * IMPORTANT: This migration should be run AFTER all code changes have been
     * deployed and verified. The is_international flag is now derived from the
     * committee table (committee.is_international) instead of being stored
     * directly on certifications and licenses.
     */
    public function up(): void
    {
        // Drop is_international from certification table
        if (Schema::hasColumn('certification', 'is_international')) {
            Schema::table('certification', function (Blueprint $table) {
                $table->dropColumn('is_international');
            });
        }

        // Drop is_international from license table
        if (Schema::hasColumn('license', 'is_international')) {
            Schema::table('license', function (Blueprint $table) {
                $table->dropColumn('is_international');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * Restores the is_international columns and repopulates them based on
     * the committee's is_international flag.
     */
    public function down(): void
    {
        // Restore is_international to certification table
        if (! Schema::hasColumn('certification', 'is_international')) {
            Schema::table('certification', function (Blueprint $table) {
                $table->boolean('is_international')->default(false)->after('acronym');
            });

            // Repopulate based on committee
            DB::table('certification')
                ->join('committee', 'certification.committee_id', '=', 'committee.id')
                ->where('committee.is_international', true)
                ->update(['certification.is_international' => true]);
        }

        // Restore is_international to license table
        if (! Schema::hasColumn('license', 'is_international')) {
            Schema::table('license', function (Blueprint $table) {
                $table->boolean('is_international')->default(false)->after('is_school_license');
            });

            // Repopulate based on committee
            DB::table('license')
                ->join('committee', 'license.committee_id', '=', 'committee.id')
                ->where('committee.is_international', true)
                ->update(['license.is_international' => true]);
        }
    }
};
