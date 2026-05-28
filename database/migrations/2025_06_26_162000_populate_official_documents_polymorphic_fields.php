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
        // Populate owner_type and owner_id for existing records
        DB::table('official_documents')
            ->whereNotNull('individual_id')
            ->whereNull('owner_type')
            ->update([
                'owner_type' => 'Domain\Individuals\Models\Individual',
                'owner_id' => DB::raw('individual_id'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear the polymorphic fields
        DB::table('official_documents')
            ->where('owner_type', 'Domain\Individuals\Models\Individual')
            ->update([
                'owner_type' => null,
                'owner_id' => null,
            ]);
    }
};
