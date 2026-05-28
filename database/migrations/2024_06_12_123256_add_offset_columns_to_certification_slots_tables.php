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
        Schema::table('certification', function (Blueprint $table) {
            $table->integer('offset_initial')->default(0);
            $table->integer('offset_current')->default(0);
        });

        Schema::table('certifications_slot', function (Blueprint $table) {
            $table->integer('numeration_from')->nullable();
            $table->integer('numeration_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification_slots_tables', function (Blueprint $table) {
            //
        });
    }
};
