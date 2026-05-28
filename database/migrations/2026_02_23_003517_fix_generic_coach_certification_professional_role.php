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
        DB::table('certification')
            ->whereIn('id', [241, 242])
            ->whereNull('professional_role_id')
            ->update(['professional_role_id' => 56]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('certification')
            ->whereIn('id', [241, 242])
            ->where('professional_role_id', 56)
            ->update(['professional_role_id' => null]);
    }
};
