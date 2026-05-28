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
        // Create DIVINGSERVICES committee (non-international diving services)
        DB::table('committee')->insert([
            'code' => 'DIVINGSERVICES',
            'name' => 'Diving Services Committee',
            'is_international' => false,
        ]);

        // Update DIVING committee to be international (CMAS Diving)
        DB::table('committee')
            ->where('code', 'DIVING')
            ->update([
                'is_international' => true,
                'name' => 'CMAS Diving Committee',
            ]);

        // Update SCIENTIFIC committee to be international (CMAS Scientific)
        DB::table('committee')
            ->where('code', 'SCIENTIFIC')
            ->update([
                'is_international' => true,
                'name' => 'CMAS Scientific Committee',
            ]);

        // Ensure SPORT is non-international
        DB::table('committee')
            ->where('code', 'SPORT')
            ->update([
                'is_international' => false,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete DIVINGSERVICES committee
        DB::table('committee')
            ->where('code', 'DIVINGSERVICES')
            ->delete();

        // Restore original names
        DB::table('committee')
            ->where('code', 'DIVING')
            ->update([
                'is_international' => false,
                'name' => 'Technical Committee',
            ]);

        DB::table('committee')
            ->where('code', 'SCIENTIFIC')
            ->update([
                'is_international' => false,
                'name' => 'Scientific Committee',
            ]);
    }
};
