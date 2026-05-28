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
        Schema::table('evt_athletes_enrollment', function (Blueprint $table) {
            $table->foreignUuid('individual_id')->nullable()->constrained('individual');
        });

        Schema::table('evt_coaches_enrollment', function (Blueprint $table) {
            $table->foreignUuid('individual_id')->nullable()->constrained('individual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_athletes_enrollment', function (Blueprint $table) {
            $table->dropForeign(['individual_id']);
            $table->dropColumn('individual_id');
        });

        Schema::table('evt_coaches_enrollment', function (Blueprint $table) {
            $table->dropForeign(['individual_id']);
            $table->dropColumn('individual_id');
        });
    }
};
