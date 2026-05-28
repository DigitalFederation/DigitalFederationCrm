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
        // Update official documents that have the full class name to use the morph alias
        DB::table('official_documents')
            ->where('owner_type', 'Domain\\Entities\\Models\\Entity')
            ->update(['owner_type' => 'entity']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to full class name (though this is not recommended)
        DB::table('official_documents')
            ->where('owner_type', 'entity')
            ->update(['owner_type' => 'Domain\\Entities\\Models\\Entity']);
    }
};
