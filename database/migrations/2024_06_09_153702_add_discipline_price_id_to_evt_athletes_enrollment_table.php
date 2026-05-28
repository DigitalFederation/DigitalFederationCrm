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
            $table->unsignedBigInteger('discipline_pricing_id')->nullable();
            $table->foreign('discipline_pricing_id')->references('id')->on('evt_pricing')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_athletes_enrollment', function (Blueprint $table) {
            //
        });
    }
};
