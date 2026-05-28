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
        // Individual permissions
        DB::table('permissions')
            ->where(function ($query) {
                $query->where('name', 'like', '%individual%')
                    ->orWhere('name', 'like', '%athlete%')
                    ->orWhere('name', 'like', '%coach%')
                    ->orWhere('name', 'like', '%instructor%')
                    ->orWhere('name', 'like', '%diver%')
                    ->orWhere('name', 'like', '%judge%')
                    ->orWhere('name', 'like', '%referee%')
                    ->orWhere('name', 'like', '%leader%')
                    ->orWhere('name', 'like', '%student%')
                    ->orWhere('name', 'like', '%lms%');
            })
            ->update(['category' => 'individual']);

        // Federation permissions (includes associations and diving/sport/scientific)
        DB::table('permissions')
            ->where(function ($query) {
                $query->where('name', 'like', '%federation%')
                    ->orWhere('name', 'like', '%association%')
                    ->orWhere('name', 'like', '%diving%')
                    ->orWhere('name', 'like', '%scientific%')
                    ->orWhere('name', 'like', '%territorial%')
                    ->orWhere('name', 'like', '%sport%')
                    ->orWhere('name', 'like', '%cmas%')
                    ->orWhere('name', 'like', '%freediving%');
            })
            ->whereNull('category') // Don't override individual ones that might have these keywords
            ->update(['category' => 'federation']);

        // Entity permissions
        DB::table('permissions')
            ->where(function ($query) {
                $query->where('name', 'like', '%entity%')
                    ->orWhere('name', 'like', '%entities%')
                    ->orWhere('name', 'like', '%club%');
            })
            ->whereNull('category')
            ->update(['category' => 'entity']);

        // System permissions (permissions management, roles, etc.)
        DB::table('permissions')
            ->where(function ($query) {
                $query->where('name', 'like', '%permission%')
                    ->orWhere('name', 'like', '%role%')
                    ->orWhere('name', 'like', '%admin%')
                    ->orWhere('name', 'like', '%menu%')
                    ->orWhere('name', 'like', '%system%');
            })
            ->whereNull('category')
            ->update(['category' => 'system']);

        // All remaining permissions default to system
        DB::table('permissions')
            ->whereNull('category')
            ->update(['category' => 'system']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset categories but keep the 3 that existed before
        DB::table('permissions')
            ->whereNotIn('id', function ($query) {
                $query->select('id')
                    ->from('permissions')
                    ->where('category', 'Permissions');
            })
            ->update(['category' => null]);
    }
};
