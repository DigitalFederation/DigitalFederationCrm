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
        Schema::table('entity_athletes', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['individual_id']);

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
        Schema::table('entity_athletes', function (Blueprint $table) {
            //
        });
    }
};
