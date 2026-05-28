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
            $table->boolean('broadcast')->nullable();
            $table->string('broadcast_information')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_events', function (Blueprint $table) {
            $table->dropColumn('broadcast');
            $table->dropColumn('broadcast_information');
        });
    }
};
