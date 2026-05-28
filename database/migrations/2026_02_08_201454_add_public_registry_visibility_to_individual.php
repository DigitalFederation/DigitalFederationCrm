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
        Schema::table('individual', function (Blueprint $table) {
            $table->boolean('visible_in_coach_registry')->default(true);
            $table->boolean('visible_in_technical_official_registry')->default(true);
            $table->boolean('visible_in_diving_professional_registry')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('individual', function (Blueprint $table) {
            $table->dropColumn([
                'visible_in_coach_registry',
                'visible_in_technical_official_registry',
                'visible_in_diving_professional_registry',
            ]);
        });
    }
};
