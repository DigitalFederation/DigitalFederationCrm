<?php

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Normalize document owner_type values to use morph aliases
     * instead of full class names.
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

    /**
     * Reverse the migrations.
     */
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
