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
        // Remove orphaned records
        DB::table('individual_federation')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('individual')
                    ->whereColumn('individual.id', 'individual_federation.individual_id');
            })
            ->delete();

        Schema::table('individual_federation', function (Blueprint $table) {
            $foreignKeyExists = $this->foreignKeyExists('individual_federation', 'individual_federation_individual_id_foreign');

            if ($foreignKeyExists) {
                // Drop the existing foreign key constraint
                $table->dropForeign(['individual_id']);
            }

            // Re-add the foreign key constraint with ON DELETE CASCADE
            $table->foreign('individual_id')
                ->references('id')
                ->on('individual')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_federation', function (Blueprint $table) {
            $table->dropForeign(['individual_id']);

            // Re-add the original foreign key without CASCADE
            $table->foreign('individual_id')
                ->references('id')
                ->on('individual');
        });
    }

    /**
     * Check if a foreign key constraint exists.
     *
     * @param  string  $table
     * @param  string  $constraintName
     * @return bool
     */
    private function foreignKeyExists($table, $constraintName)
    {
        return DB::select("
            SELECT *
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = '{$table}'
            AND CONSTRAINT_NAME = '{$constraintName}'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ") ? true : false;
    }
};
