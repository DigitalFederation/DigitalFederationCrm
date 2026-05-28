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
            $table->string('event_category')->nullable();
            $table->unsignedBigInteger('geo_zone_id')->nullable();
            $table->date('candidacy_limit_date')->nullable();
            $table->decimal('candidacy_fee', 8, 2)->nullable();
            $table->string('status_class')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_events', function (Blueprint $table) {
            $table->dropColumn('event_category');
            $table->dropColumn('geo_zone_id');
            $table->dropColumn('candidacy_limit_date');
            $table->dropColumn('candidacy_fee');
        });
    }
};
