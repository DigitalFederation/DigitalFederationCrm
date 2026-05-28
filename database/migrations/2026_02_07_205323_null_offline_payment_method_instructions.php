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
        DB::table('payment_method')
            ->where('driver', 'offline')
            ->update(['instructions' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('payment_method')
            ->where('driver', 'offline')
            ->update(['instructions' => 'Please follow the payment instructions provided by your administrator.']);
    }
};
