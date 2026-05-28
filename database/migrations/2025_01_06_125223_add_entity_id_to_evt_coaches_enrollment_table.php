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
        Schema::table('evt_coaches_enrollment', static function (Blueprint $table) {
            $table->foreignId('entity_id')
                ->nullable()
                ->constrained('entity')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_coaches_enrollment', function (Blueprint $table) {
            //
        });
    }
};
