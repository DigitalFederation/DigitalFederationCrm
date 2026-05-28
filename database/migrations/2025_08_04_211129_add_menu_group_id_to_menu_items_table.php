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
        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreignId('menu_group_id')
                ->nullable()
                ->after('menu_id')
                ->constrained('menu_groups')
                ->onDelete('set null')
                ->comment('Optional group assignment');

            // Add index for group queries
            $table->index(['menu_id', 'menu_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['menu_group_id']);
            $table->dropIndex(['menu_id', 'menu_group_id']);
            $table->dropColumn('menu_group_id');
        });
    }
};
