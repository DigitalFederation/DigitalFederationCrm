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
        Schema::table('individual', function (Blueprint $table) {
            // First, drop the existing foreign key constraint
            $table->dropForeign(['user_id']);

            // Modify the column to allow NULL values
            $table->uuid('user_id')->nullable()->change();

            // Then, add the new foreign key constraint
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }
};
