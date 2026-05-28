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
        Schema::table('diving_entity_technical_directors', function (Blueprint $table) {
            // Check if columns don't already exist before adding them
            if (! Schema::hasColumn('diving_entity_technical_directors', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('assigned_at');
            }
            if (! Schema::hasColumn('diving_entity_technical_directors', 'approval_notes')) {
                $table->text('approval_notes')->nullable()->after('approved_at');
            }
            if (! Schema::hasColumn('diving_entity_technical_directors', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approval_notes');
            }
            if (! Schema::hasColumn('diving_entity_technical_directors', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
        });

        // Add indexes separately to avoid issues if they already exist
        Schema::table('diving_entity_technical_directors', function (Blueprint $table) {
            // Add indexes for performance with shorter names
            if (! collect(DB::select('SHOW INDEXES FROM diving_entity_technical_directors'))->pluck('Key_name')->contains('tech_dir_approved_at_idx')) {
                $table->index('approved_at', 'tech_dir_approved_at_idx');
            }
            if (! collect(DB::select('SHOW INDEXES FROM diving_entity_technical_directors'))->pluck('Key_name')->contains('tech_dir_rejected_at_idx')) {
                $table->index('rejected_at', 'tech_dir_rejected_at_idx');
            }
            if (! collect(DB::select('SHOW INDEXES FROM diving_entity_technical_directors'))->pluck('Key_name')->contains('tech_dir_license_approval_idx')) {
                $table->index(['license_attributed_id', 'approved_at'], 'tech_dir_license_approval_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_entity_technical_directors', function (Blueprint $table) {
            $table->dropIndex('tech_dir_license_approval_idx');
            $table->dropIndex('tech_dir_rejected_at_idx');
            $table->dropIndex('tech_dir_approved_at_idx');

            $table->dropColumn([
                'approved_at',
                'approval_notes',
                'rejected_at',
                'rejection_reason',
            ]);
        });
    }
};
