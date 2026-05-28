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
        Schema::create('evt_discipline_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discipline_id');
            $table->index(['discipline_id'])->foreign(['discipline_id'])
                ->references('id')->on('evt_disciplines')->onDelete('restrict')->onUpdate('restrict');

            $table->unsignedInteger('license_id');
            $table->index(['license_id'])->foreign(['license_id'])
                ->references('id')->on('license')->onDelete('restrict')->onUpdate('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_discipline_licenses');
    }
};
