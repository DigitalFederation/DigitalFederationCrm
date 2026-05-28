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
        // First, update all existing records to have allow_entity_group_request = 1
        DB::table('license')->update(['allow_entity_group_request' => 1]);

        // Then, modify the column to have a default value of 1
        Schema::table('license', function (Blueprint $table) {
            $table->boolean('allow_entity_group_request')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the column default back to 0
        Schema::table('license', function (Blueprint $table) {
            $table->boolean('allow_entity_group_request')->default(0)->change();
        });

        // Note: We don't revert the data changes as that could break existing functionality
    }
};
