<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns exist first
        $hasOwnerType = Schema::hasColumn('official_documents', 'owner_type');
        $hasOwnerId = Schema::hasColumn('official_documents', 'owner_id');

        if (! $hasOwnerType || ! $hasOwnerId) {
            Schema::table('official_documents', function (Blueprint $table) use ($hasOwnerType, $hasOwnerId) {
                // Add polymorphic columns if they don't exist
                if (! $hasOwnerType) {
                    $table->string('owner_type')->nullable()->after('individual_id');
                }
                if (! $hasOwnerId) {
                    $table->uuid('owner_id')->nullable()->after('owner_type');
                }
            });
        }

        // Try to add index, it will fail silently if it already exists
        try {
            Schema::table('official_documents', function (Blueprint $table) {
                $table->index(['owner_type', 'owner_id'], 'official_documents_owner_type_owner_id_index');
            });
        } catch (\Exception $e) {
            // Index already exists, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('official_documents', function (Blueprint $table) {
            // Drop index first
            $table->dropIndex('official_documents_owner_type_owner_id_index');

            // Drop columns
            $table->dropColumn(['owner_type', 'owner_id']);
        });
    }
};
