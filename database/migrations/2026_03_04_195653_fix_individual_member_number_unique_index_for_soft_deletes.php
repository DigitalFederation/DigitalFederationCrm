<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace the plain unique index on member_number with a functional unique
     * index that only enforces uniqueness for non-soft-deleted records.
     *
     * MySQL 8.0+ supports functional indexes, so we use a generated column
     * approach: drop the old unique index and create a new unique index on
     * a virtual column that is NULL when the record is soft-deleted.
     */
    public function up(): void
    {
        Schema::table('individual', function (Blueprint $table) {
            $table->dropUnique('individual_member_number_unique');
        });

        // Add a virtual column that returns member_number only for non-deleted records.
        // NULL values are ignored by MySQL unique indexes, so soft-deleted records won't conflict.
        DB::statement('ALTER TABLE `individual` ADD COLUMN `member_number_active` BIGINT AS (IF(`deleted_at` IS NULL, `member_number`, NULL)) VIRTUAL');

        Schema::table('individual', function (Blueprint $table) {
            $table->unique('member_number_active', 'individual_member_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('individual', function (Blueprint $table) {
            $table->dropUnique('individual_member_number_unique');
            $table->dropColumn('member_number_active');
        });

        Schema::table('individual', function (Blueprint $table) {
            $table->unique('member_number', 'individual_member_number_unique');
        });
    }
};
