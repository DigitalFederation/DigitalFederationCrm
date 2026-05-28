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
        // Step 1: Add a new JSON column
        Schema::table('diving_location', function (Blueprint $table) {
            $table->json('levels')->nullable()->after('level');
        });

        // Step 2: Migrate existing data from enum to JSON array
        DB::statement("
            UPDATE diving_location
            SET levels = CASE
                WHEN level IS NOT NULL AND level != '' THEN JSON_ARRAY(level)
                ELSE NULL
            END
        ");

        // Step 3: Drop the old enum column
        Schema::table('diving_location', function (Blueprint $table) {
            $table->dropColumn('level');
        });

        // Step 4: Rename levels to level
        Schema::table('diving_location', function (Blueprint $table) {
            $table->renameColumn('levels', 'level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Add back the enum column
        Schema::table('diving_location', function (Blueprint $table) {
            $table->enum('level_old', ['Beginner', 'Intermediate', 'Advanced', 'Technical'])->nullable()->after('level');
        });

        // Step 2: Migrate data back (take first value from array)
        DB::statement("
            UPDATE diving_location
            SET level_old = JSON_UNQUOTE(JSON_EXTRACT(level, '$[0]'))
            WHERE level IS NOT NULL AND JSON_LENGTH(level) > 0
        ");

        // Step 3: Drop the JSON column
        Schema::table('diving_location', function (Blueprint $table) {
            $table->dropColumn('level');
        });

        // Step 4: Rename level_old to level
        Schema::table('diving_location', function (Blueprint $table) {
            $table->renameColumn('level_old', 'level');
        });
    }
};
