<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Convert legacy "All" string values to NULL in the requester_model field.
     * NULL means "allow all requester types" which is the correct format.
     */
    public function up(): void
    {
        DB::table('license')
            ->where('requester_model', 'All')
            ->update(['requester_model' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * No rollback needed - NULL is the correct format.
     */
    public function down(): void
    {
        // No rollback - NULL is the correct format
    }
};
