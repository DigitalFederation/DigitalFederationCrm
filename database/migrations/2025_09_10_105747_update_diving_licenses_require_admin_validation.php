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
        // Update all diving licenses to require admin validation
        DB::table('license')
            ->whereIn('committee_id', function ($query) {
                $query->select('id')
                    ->from('committee')
                    ->where('code', 'DIVING');
            })
            ->update(['requires_admin_validation' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert specific licenses that didn't require validation
        DB::table('license')
            ->whereIn('id', [14, 16, 17, 18])
            ->update(['requires_admin_validation' => false]);
    }
};
