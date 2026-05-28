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
        // Fix entity_type to use morph map aliases instead of full class names
        DB::table('event_applications')
            ->where('entity_type', 'Domain\\Entities\\Models\\Entity')
            ->update(['entity_type' => 'entity']);

        DB::table('event_applications')
            ->where('entity_type', 'Domain\\Federations\\Models\\Federation')
            ->update(['entity_type' => 'federation']);

        DB::table('event_applications')
            ->where('entity_type', 'Domain\\Individuals\\Models\\Individual')
            ->update(['entity_type' => 'individual']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes
        DB::table('event_applications')
            ->where('entity_type', 'entity')
            ->update(['entity_type' => 'Domain\\Entities\\Models\\Entity']);

        DB::table('event_applications')
            ->where('entity_type', 'federation')
            ->update(['entity_type' => 'Domain\\Federations\\Models\\Federation']);

        DB::table('event_applications')
            ->where('entity_type', 'individual')
            ->update(['entity_type' => 'Domain\\Individuals\\Models\\Individual']);
    }
};
