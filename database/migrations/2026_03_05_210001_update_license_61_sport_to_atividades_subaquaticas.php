<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sportId = DB::table('sports')
            ->where('name', 'Atividades Subaquaticas')
            ->value('id');

        if ($sportId) {
            DB::table('license')
                ->where('id', 61)
                ->update(['sport_id' => $sportId]);
        }
    }

    public function down(): void
    {
        // Revert to Finswimming (id=1)
        DB::table('license')
            ->where('id', 61)
            ->update(['sport_id' => 1]);
    }
};
