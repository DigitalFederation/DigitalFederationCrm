<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sports', function (Blueprint $table) {
            $table->string('sport_type')->nullable()->after('name');
        });

        Schema::table('evt_sports', function (Blueprint $table) {
            $table->string('sport_type')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('sports', function (Blueprint $table) {
            $table->dropColumn('sport_type');
        });

        Schema::table('evt_sports', function (Blueprint $table) {
            $table->dropColumn('sport_type');
        });
    }
};
