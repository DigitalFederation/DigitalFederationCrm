<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('sports')->insert([
            'name' => 'Atividades Subaquaticas',
            'sport_type' => 'individual',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('sports')->where('name', 'Atividades Subaquaticas')->delete();
    }
};
