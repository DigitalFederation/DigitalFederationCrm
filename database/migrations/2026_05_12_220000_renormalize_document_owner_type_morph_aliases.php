<?php

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Re-run document owner_type normalization for rows created after older
     * morph alias backfill migrations were already applied.
     */
    public function up(): void
    {
        if (! Schema::hasTable('document')) {
            return;
        }

        foreach (Relation::morphMap() as $morphAlias => $className) {
            DB::table('document')
                ->where('owner_type', $className)
                ->update(['owner_type' => $morphAlias]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('document')) {
            return;
        }

        foreach (Relation::morphMap() as $morphAlias => $className) {
            DB::table('document')
                ->where('owner_type', $morphAlias)
                ->update(['owner_type' => $className]);
        }
    }
};
