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
            $table->unsignedBigInteger('pricing_id')->nullable()->after('event_id');
            $table->foreign('pricing_id')->references('id')->on('evt_pricing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_enrollments', function (Blueprint $table) {
            $table->dropForeign(['pricing_id']);
            $table->dropColumn('pricing_id');
        });
    }
};
