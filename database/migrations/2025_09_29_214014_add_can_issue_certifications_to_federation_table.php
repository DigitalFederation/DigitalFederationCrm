<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('federation', function (Blueprint $table) {
            $table->boolean('can_issue_certifications')->default(true)->after('is_default_federation');
        });

        // Set can_issue_certifications based on current logic
        // Main federations (no parent and not local) can issue certifications
        DB::statement('
            UPDATE federation
            SET can_issue_certifications = true
            WHERE (parent_id IS NULL OR parent_id = 0)
              AND (is_local = false OR is_local IS NULL)
        ');

        // Special case: The main CMAS federation (is_default_federation) can always issue
        DB::statement('
            UPDATE federation
            SET can_issue_certifications = true
            WHERE is_default_federation = true
        ');

        // Local federations (with parent or is_local=true) cannot issue certifications by default
        DB::statement('
            UPDATE federation
            SET can_issue_certifications = false
            WHERE (parent_id IS NOT NULL AND parent_id > 0)
              OR is_local = true
        ');

        // Re-enable for the default federation if it was disabled
        DB::statement('
            UPDATE federation
            SET can_issue_certifications = true
            WHERE is_default_federation = true
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('federation', function (Blueprint $table) {
            $table->dropColumn('can_issue_certifications');
        });
    }
};
