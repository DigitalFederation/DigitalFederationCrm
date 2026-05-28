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
        // Remove the problematic migration record that might exist on staging
        DB::table('migrations')
            ->where('migration', '2025_06_30_130000_recreate_entity_professional_role_invitations_table')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this operation
    }
};
