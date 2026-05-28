<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('license')
            ->whereNotNull('sport_id')
            ->orderBy('id')
            ->each(function ($license) {
                DB::table('license_sport')->insert([
                    'license_id' => $license->id,
                    'sport_id' => $license->sport_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        DB::table('license_sport')->truncate();
    }
};
