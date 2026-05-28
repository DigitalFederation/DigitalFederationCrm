<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tables where ON DELETE CASCADE is desired
        $cascadeTables = [
            'individual_entity',
            'individual_professional_role',
            'entity_professional_role',
            'entity_athletes',
            'diving_buddies',
            'individual_sequence_logs',
            'federation_professional_role',
            'evt_officials_enrollment',
            'evt_referees_enrollment',
        ];

        // Tables where ON DELETE SET NULL is desired
        $setNullTables = [
            'official_documents',
        ];

        // Handle cascade tables
        foreach ($cascadeTables as $table) {
            // Remove orphaned records
            DB::table($table)
                ->whereNotExists(function ($query) use ($table) {
                    $query->select(DB::raw(1))
                        ->from('individual')
                        ->whereColumn('individual.id', "{$table}.individual_id");
                })
                ->delete();

            // Check if the foreign key constraint exists
            $constraintExists = DB::select("SELECT *
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_SCHEMA = DATABASE()
                AND CONSTRAINT_NAME = '{$table}_individual_id_foreign'
                AND TABLE_NAME = '{$table}'");

            if ($constraintExists) {
                // Drop existing foreign key constraint
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$table}_individual_id_foreign`");
            }

            // Add new foreign key with ON DELETE CASCADE
            DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$table}_individual_id_foreign` FOREIGN KEY (`individual_id`) REFERENCES `individual`(`id`) ON DELETE CASCADE");
        }

        // Handle set null tables
        foreach ($setNullTables as $table) {
            // Set individual_id to null where it references a non-existing individual
            DB::table($table)
                ->whereNotExists(function ($query) use ($table) {
                    $query->select(DB::raw(1))
                        ->from('individual')
                        ->whereColumn('individual.id', "{$table}.individual_id");
                })
                ->update(['individual_id' => null]);

            // Check if the foreign key constraint exists
            $constraintExists = DB::select("SELECT *
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_SCHEMA = DATABASE()
                AND CONSTRAINT_NAME = '{$table}_individual_id_foreign'
                AND TABLE_NAME = '{$table}'");

            if ($constraintExists) {
                // Drop existing foreign key constraint
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$table}_individual_id_foreign`");
            }
            // Add new foreign key with ON DELETE SET NULL
            DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$table}_individual_id_foreign` FOREIGN KEY (`individual_id`) REFERENCES `individual`(`id`) ON DELETE SET NULL");
        }
    }

    public function down()
    {
        $cascadeTables = [
            'individual_entity',
            'individual_professional_role',
            'entity_professional_role',
            'entity_athletes',
            'diving_buddies',
            'individual_sequence_logs',
            'federation_professional_role',
            'evt_officials_enrollment',
            'evt_referees_enrollment',
        ];

        $setNullTables = [
            'official_documents',
        ];

        $tables = array_merge($cascadeTables, $setNullTables);

        foreach ($tables as $table) {
            // Drop foreign key constraint on individual_id
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['individual_id']);
            });

            // If the table was in setNullTables, modify the column to be not nullable
            if (in_array($table, $setNullTables)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->uuid('individual_id')->nullable(false)->change();
                });
            }

            // Add foreign key without ON DELETE action
            Schema::table($table, function (Blueprint $table) {
                $table->foreign('individual_id')
                    ->references('id')
                    ->on('individual');
            });
        }
    }
};
