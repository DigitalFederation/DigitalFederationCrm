<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Move all non-international DIVING certifications to DIVINGSERVICES.
     * Keep international DIVING certifications in DIVING committee.
     */
    public function up(): void
    {
        $divingServicesId = DB::table('committee')
            ->where('code', 'DIVINGSERVICES')
            ->value('id');

        $divingId = DB::table('committee')
            ->where('code', 'DIVING')
            ->value('id');

        if (! $divingServicesId || ! $divingId) {
            // Skip migration if committees don't exist (e.g., in test environment)
            return;
        }

        // Move non-international DIVING certifications to DIVINGSERVICES
        $affected = DB::table('certification')
            ->where('committee_id', $divingId)
            ->where('is_international', false)
            ->update(['committee_id' => $divingServicesId]);

        // Log the migration result
        \Log::info("Migrated {$affected} certifications from DIVING to DIVINGSERVICES");
    }

    /**
     * Reverse the migrations.
     *
     * Move all DIVINGSERVICES certifications back to DIVING.
     */
    public function down(): void
    {
        $divingServicesId = DB::table('committee')
            ->where('code', 'DIVINGSERVICES')
            ->value('id');

        $divingId = DB::table('committee')
            ->where('code', 'DIVING')
            ->value('id');

        if ($divingServicesId && $divingId) {
            DB::table('certification')
                ->where('committee_id', $divingServicesId)
                ->update(['committee_id' => $divingId]);
        }
    }
};
