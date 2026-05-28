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
        Schema::create('evt_competition_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->index()->constrained('evt_competitions');
            $table->string('competition_type');
            $table->timestamps();
        });

        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropColumn('competition_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_competition_types');
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->string('competition_type');
        });
    }
};
