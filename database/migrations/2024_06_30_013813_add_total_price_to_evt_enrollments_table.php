<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evt_enrollments', function (Blueprint $table) {
            $table->float('total_price')->nullable()->after('payment_status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_enrollments', function (Blueprint $table) {
            $table->dropColumn('total_price');
        });
    }
};
