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
        // Change location on evt_events table to nullable
        Schema::table('evt_events', function (Blueprint $table) {
            $table->string('location')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change location on evt_events table to not nullable
        Schema::table('evt_events', function (Blueprint $table) {
            $table->string('location')->nullable(false)->change();
        });
    }
};
