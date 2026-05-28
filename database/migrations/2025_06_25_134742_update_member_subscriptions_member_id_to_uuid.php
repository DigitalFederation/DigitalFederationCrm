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
        // For safety, check if the column type is already correct
        $memberIdColumn = DB::select("SHOW COLUMNS FROM member_subscriptions WHERE Field = 'member_id'");

        if (! empty($memberIdColumn) && str_contains($memberIdColumn[0]->Type, 'char')) {
            // Column is already varchar/char (UUID), nothing to do
            return;
        }

        // Check if we need to migrate (in case of existing data)
        $hasData = DB::table('member_subscriptions')
            ->whereNotNull('member_id')
            ->where('member_id', '!=', '')
            ->exists();

        if ($hasData) {
            // This migration requires manual data migration as IDs need to be converted
            throw new \Exception(
                'Cannot automatically migrate member_subscriptions table: existing data found with integer member_id values. ' .
                'Please manually migrate the data or clear the member_subscriptions table before running this migration.'
            );
        }

        // Drop the existing morphs columns (this also drops the indexes)
        Schema::table('member_subscriptions', function (Blueprint $table) {
            $table->dropMorphs('member');
        });

        // Now add the UUID morphs columns (this automatically creates the index)
        Schema::table('member_subscriptions', function (Blueprint $table) {
            $table->uuidMorphs('member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the UUID morphs columns
        Schema::table('member_subscriptions', function (Blueprint $table) {
            $table->dropIndex(['member_type', 'member_id']);
            $table->dropMorphs('member');
        });

        // Restore the original morphs columns
        Schema::table('member_subscriptions', function (Blueprint $table) {
            $table->morphs('member');
        });
    }
};
