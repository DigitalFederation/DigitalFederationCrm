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
        Schema::table('evt_events', function (Blueprint $table) {
            $table->decimal('event_fee', 8, 2)->nullable()->after('name');
            // Enrollment fee type: per person or flat fee
            $table->string('event_fee_type')->nullable()->after('event_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_events', function (Blueprint $table) {
            $table->dropColumn('event_fee');
            $table->dropColumn('event_fee_type');
        });
    }
};
