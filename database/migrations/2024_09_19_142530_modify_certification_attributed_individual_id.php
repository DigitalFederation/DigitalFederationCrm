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
        Schema::table('certification_attributed', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['individual_id']);

            // Modify the individual_id column to be nullable
            $table->uuid('individual_id')->nullable()->change();

            // Re-add the foreign key constraint with ON DELETE SET NULL
            $table->foreign('individual_id')
                ->references('id')
                ->on('individual')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification_attributed', function (Blueprint $table) {
            Schema::table('certification_attributed', function (Blueprint $table) {
                // Drop the modified foreign key constraint
                $table->dropForeign(['individual_id']);

                // Revert the individual_id column to be NOT nullable
                $table->uuid('individual_id')->nullable(false)->change();

                // Re-add the original foreign key constraint (assuming it was ON DELETE RESTRICT)
                $table->foreign('individual_id')
                    ->references('id')
                    ->on('individual')
                    ->onDelete('restrict');
            });
        });
    }
};
