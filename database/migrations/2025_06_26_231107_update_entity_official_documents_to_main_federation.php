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
        // Get the main federation ID
        $mainFederation = DB::table('federation')
            ->where('is_default_federation', true)
            ->first();

        if ($mainFederation) {
            // Update all entity official documents to use the main federation
            DB::table('official_documents')
                ->where('owner_type', 'entity')
                ->update(['federation_id' => $mainFederation->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We can't reliably reverse this migration as we don't know
        // what the original federation_id values were
        // This is intentional as all entity documents should use the main federation
    }
};
