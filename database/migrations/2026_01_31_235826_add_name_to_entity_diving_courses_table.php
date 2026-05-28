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
        // Step 1: Add the name column if it doesn't exist
        if (! Schema::hasColumn('entity_diving_courses', 'name')) {
            Schema::table('entity_diving_courses', function (Blueprint $table) {
                $table->string('name')->nullable()->after('entity_id');
            });
        }

        // Step 2: Migrate existing data - copy certification name to the new name field
        DB::statement('
            UPDATE entity_diving_courses edc
            INNER JOIN certification c ON edc.certification_id = c.id
            SET edc.name = c.name
            WHERE edc.certification_id IS NOT NULL AND edc.name IS NULL
        ');

        // Step 3: Drop foreign key on entity_id first (it references the unique index)
        $fkExists = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'entity_diving_courses'
            AND CONSTRAINT_NAME = 'entity_diving_courses_entity_id_foreign'
        "))->isNotEmpty();

        if ($fkExists) {
            Schema::table('entity_diving_courses', function (Blueprint $table) {
                $table->dropForeign(['entity_id']);
            });
        }

        // Step 4: Drop the unique index if it exists
        $indexExists = collect(DB::select("SHOW INDEX FROM entity_diving_courses WHERE Key_name = 'entity_cert_start_unique'"))->isNotEmpty();
        if ($indexExists) {
            Schema::table('entity_diving_courses', function (Blueprint $table) {
                $table->dropUnique('entity_cert_start_unique');
            });
        }

        // Step 5: Make certification_id nullable
        Schema::table('entity_diving_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('certification_id')->nullable()->change();
        });

        // Step 6: Re-add the foreign key on entity_id
        Schema::table('entity_diving_courses', function (Blueprint $table) {
            $table->foreign('entity_id')->references('id')->on('entity')->onDelete('cascade');
        });

        // Step 7: Add new unique constraint on name if it doesn't exist
        $indexExists = collect(DB::select("SHOW INDEX FROM entity_diving_courses WHERE Key_name = 'entity_name_start_unique'"))->isNotEmpty();
        if (! $indexExists) {
            Schema::table('entity_diving_courses', function (Blueprint $table) {
                $table->unique(['entity_id', 'name', 'start_date'], 'entity_name_start_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $indexExists = collect(DB::select("SHOW INDEX FROM entity_diving_courses WHERE Key_name = 'entity_name_start_unique'"))->isNotEmpty();
        if ($indexExists) {
            Schema::table('entity_diving_courses', function (Blueprint $table) {
                $table->dropUnique('entity_name_start_unique');
            });
        }

        Schema::table('entity_diving_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('certification_id')->nullable(false)->change();
        });

        $indexExists = collect(DB::select("SHOW INDEX FROM entity_diving_courses WHERE Key_name = 'entity_cert_start_unique'"))->isNotEmpty();
        if (! $indexExists) {
            Schema::table('entity_diving_courses', function (Blueprint $table) {
                $table->unique(['entity_id', 'certification_id', 'start_date'], 'entity_cert_start_unique');
            });
        }

        if (Schema::hasColumn('entity_diving_courses', 'name')) {
            Schema::table('entity_diving_courses', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
};
