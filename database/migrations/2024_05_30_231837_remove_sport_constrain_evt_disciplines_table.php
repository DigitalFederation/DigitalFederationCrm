<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evt_disciplines', function (Blueprint $table) {
            // Drop existing foreign key and column
            $table->dropForeign(['sport_id']);
            $table->foreign('sport_id')->references('id')->on('sports');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}

};
