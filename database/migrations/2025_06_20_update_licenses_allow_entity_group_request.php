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
        // Update all individual licenses to allow entity group purchases
        // This allows entities to purchase any individual license for their members
        DB::table('license')
            ->where('type_id', 2) // Individual license type
            ->update(['allow_entity_group_request' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset all licenses to not allow entity group requests
        DB::table('license')
            ->update(['allow_entity_group_request' => false]);
    }
};
