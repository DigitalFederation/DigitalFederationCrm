<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Open-source debrand: the "cmas-diving" route namespace was renamed to
 * "international-diving". This keeps DB-stored route identifiers in sync for
 * existing installations (menu links and route-permission enforcement match
 * route names). No-op on fresh installs where no such rows exist yet.
 *
 * The "cmas-diving-admin" ROLE is intentionally NOT affected — only route
 * identifiers (route_name / active_patterns / route_pattern) are rewritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->rewrite('cmas-diving', 'international-diving');
    }

    public function down(): void
    {
        $this->rewrite('international-diving', 'cmas-diving');
    }

    private function rewrite(string $from, string $to): void
    {
        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')
                ->where('route_name', 'like', "%{$from}%")
                ->update(['route_name' => DB::raw("REPLACE(route_name, '{$from}', '{$to}')")]);

            if (Schema::hasColumn('menu_items', 'active_patterns')) {
                DB::table('menu_items')
                    ->where('active_patterns', 'like', "%{$from}%")
                    ->update(['active_patterns' => DB::raw("REPLACE(active_patterns, '{$from}', '{$to}')")]);
            }
        }

        if (Schema::hasTable('route_permissions')) {
            DB::table('route_permissions')
                ->where('route_pattern', 'like', "%{$from}%")
                ->update(['route_pattern' => DB::raw("REPLACE(route_pattern, '{$from}', '{$to}')")]);
        }
    }
};
