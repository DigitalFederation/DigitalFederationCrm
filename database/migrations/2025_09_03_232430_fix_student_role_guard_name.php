<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix the student role that has 'sanctum' guard instead of 'web'
        DB::table('roles')
            ->where('name', 'student')
            ->where('guard_name', 'sanctum')
            ->update(['guard_name' => 'web']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to sanctum if needed (though not recommended)
        DB::table('roles')
            ->where('name', 'student')
            ->where('guard_name', 'web')
            ->update(['guard_name' => 'sanctum']);
    }
};
