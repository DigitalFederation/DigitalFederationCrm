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
        Schema::table('evt_officials_enrollment', function (Blueprint $table) {
            $table->foreignUuid('individual_id')->nullable()->constrained('individual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_officials_enrollment', function (Blueprint $table) {
            $table->dropColumn('individual_id');
        });
    }
};
