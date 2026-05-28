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
        // Check if columns already exist
        $hasRequesterType = Schema::hasColumn('insurances', 'requester_type');
        $hasRequesterId = Schema::hasColumn('insurances', 'requester_id');
        $hasRequestType = Schema::hasColumn('insurances', 'request_type');
        $hasRequestedByUserId = Schema::hasColumn('insurances', 'requested_by_user_id');

        if (! $hasRequesterType || ! $hasRequesterId || ! $hasRequestType) {
            Schema::table('insurances', function (Blueprint $table) use ($hasRequesterType, $hasRequesterId, $hasRequestType) {
                if (! $hasRequesterType) {
                    $table->string('requester_type')->nullable()->after('member_subscription_id');
                }
                if (! $hasRequesterId) {
                    $table->char('requester_id', 36)->nullable()->after('requester_type');
                }
                if (! $hasRequestType) {
                    $table->enum('request_type', ['direct', 'entity_group'])->default('direct')->after('requester_id');
                }

                if (! $hasRequesterType || ! $hasRequesterId) {
                    $table->index(['requester_type', 'requester_id']);
                }
            });
        }

        // Migrate existing data if requested_by_user_id exists
        if ($hasRequestedByUserId) {
            DB::statement("
                UPDATE insurances 
                SET requester_type = 'App\\\\Models\\\\User',
                    requester_id = requested_by_user_id,
                    request_type = 'direct'
                WHERE requested_by_user_id IS NOT NULL
                  AND requester_type IS NULL
            ");

            // Drop the old column
            Schema::table('insurances', function (Blueprint $table) {
                if (Schema::hasColumn('insurances', 'requested_by_user_id')) {
                    $table->dropForeign(['requested_by_user_id']);
                    $table->dropColumn('requested_by_user_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the old column if it doesn't exist
        if (! Schema::hasColumn('insurances', 'requested_by_user_id')) {
            Schema::table('insurances', function (Blueprint $table) {
                $table->char('requested_by_user_id', 36)->nullable()->after('member_subscription_id');
                $table->foreign('requested_by_user_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        // Migrate data back to the old column (only for User types)
        DB::statement("
            UPDATE insurances 
            SET requested_by_user_id = requester_id
            WHERE requester_type = 'App\\\\Models\\\\User'
              AND requested_by_user_id IS NULL
        ");

        // Drop new columns if they exist
        Schema::table('insurances', function (Blueprint $table) {
            if (Schema::hasColumn('insurances', 'requester_type') && Schema::hasColumn('insurances', 'requester_id')) {
                $table->dropIndex(['requester_type', 'requester_id']);
            }

            $columnsToDrop = [];
            if (Schema::hasColumn('insurances', 'requester_type')) {
                $columnsToDrop[] = 'requester_type';
            }
            if (Schema::hasColumn('insurances', 'requester_id')) {
                $columnsToDrop[] = 'requester_id';
            }
            if (Schema::hasColumn('insurances', 'request_type')) {
                $columnsToDrop[] = 'request_type';
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
