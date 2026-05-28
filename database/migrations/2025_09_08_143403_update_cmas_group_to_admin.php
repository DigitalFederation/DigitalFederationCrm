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
        // Update the CMAS group code to ADMIN
        DB::table('user_group')
            ->where('code', 'CMAS')
            ->update([
                'code' => 'ADMIN',
                'name' => 'Admin',
            ]);

        // Log the update for audit purposes
        \Log::info('Migration: Updated CMAS group code to ADMIN');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the ADMIN group code back to CMAS
        DB::table('user_group')
            ->where('code', 'ADMIN')
            ->where('id', 4) // Ensure we're only updating the original CMAS group
            ->update([
                'code' => 'CMAS',
                'name' => 'Cmas',
            ]);

        // Log the rollback for audit purposes
        \Log::info('Migration Rollback: Reverted ADMIN group code back to CMAS');
    }
};
