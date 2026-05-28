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
        // System roles
        DB::table('roles')
            ->whereIn('name', ['admin', 'admin-notifications'])
            ->update(['scope' => 'system']);

        // Federation roles (includes associations)
        DB::table('roles')
            ->where(function ($query) {
                $query->where('name', 'like', 'federation-%')
                    ->orWhere('name', 'like', 'association-%');
            })
            ->update(['scope' => 'federation']);

        // Entity roles
        DB::table('roles')
            ->where('name', 'like', 'entity-%')
            ->update(['scope' => 'entity']);

        // Individual roles
        DB::table('roles')
            ->where(function ($query) {
                $query->where('name', 'like', 'individual-%')
                    ->orWhere('name', 'like', 'view-individual-%')
                    ->orWhere('name', '=', 'student');
            })
            ->update(['scope' => 'individual']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set all scopes back to NULL
        DB::table('roles')->update(['scope' => null]);
    }
};
