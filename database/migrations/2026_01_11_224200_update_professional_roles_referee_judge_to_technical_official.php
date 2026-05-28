<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Merges REFEREE, JUDGE, and REFEREEJUDGE role types into TECHNICAL_OFFICIAL
     * as per PM standardization request.
     */
    public function up(): void
    {
        // Update professional_roles table
        DB::table('professional_roles')
            ->whereIn('role', ['REFEREE', 'JUDGE', 'REFEREEJUDGE'])
            ->update(['role' => 'TECHNICAL_OFFICIAL']);

        // Update evt_attributes enrollment_type
        DB::table('evt_attributes')
            ->where('enrollment_type', 'REFEREE')
            ->update(['enrollment_type' => 'TECHNICAL_OFFICIAL']);

        // Update evt_pricing enrollment_role
        DB::table('evt_pricing')
            ->where('enrollment_role', 'REFEREE')
            ->update(['enrollment_role' => 'TECHNICAL_OFFICIAL']);
    }

    /**
     * Reverse the migrations.
     *
     * Note: This cannot perfectly restore the original values since we don't know
     * which records were REFEREE vs JUDGE vs REFEREEJUDGE before the migration.
     * The down() is a no-op - manual intervention would be required to revert.
     */
    public function down(): void
    {
        // Cannot restore original values - would require data backup
    }
};
