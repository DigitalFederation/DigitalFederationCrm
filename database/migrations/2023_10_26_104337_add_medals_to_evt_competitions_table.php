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
        Schema::table('evt_competitions', function (Blueprint $table) {
            // N of gold medals
            $table->integer('medals_gold')->default(0);
            // N of silver medals
            $table->integer('medals_silver')->default(0);
            // N of bronze medals
            $table->integer('medals_bronze')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropColumn('medals_gold');
            $table->dropColumn('medals_silver');
            $table->dropColumn('medals_bronze');
        });
    }
};
