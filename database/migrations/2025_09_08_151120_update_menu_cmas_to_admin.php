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
        // Update the CMAS menu to Admin menu
        DB::table('menus')
            ->where('id', 1)
            ->where('machine_name', 'cmas')
            ->update([
                'machine_name' => 'admin',
                'name' => 'Admin Menu',
                'description' => 'Main navigation menu for administrators',
                'updated_at' => now(),
            ]);

        // Log the update for audit purposes
        if (DB::table('menus')->where('machine_name', 'admin')->exists()) {
            \Log::info('Successfully migrated CMAS menu to Admin menu');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the Admin menu back to CMAS menu
        DB::table('menus')
            ->where('id', 1)
            ->where('machine_name', 'admin')
            ->update([
                'machine_name' => 'cmas',
                'name' => 'CMAS Admin Menu',
                'description' => 'Main navigation menu for CMAS administrators',
                'updated_at' => now(),
            ]);
    }
};
