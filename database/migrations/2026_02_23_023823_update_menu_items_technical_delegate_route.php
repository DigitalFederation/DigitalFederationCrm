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
        DB::table('menu_items')
            ->where('route_name', 'individual.event-judge.index')
            ->update(['route_name' => 'individual.technical-delegate.index']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('menu_items')
            ->where('route_name', 'individual.technical-delegate.index')
            ->update(['route_name' => 'individual.event-judge.index']);
    }
};
