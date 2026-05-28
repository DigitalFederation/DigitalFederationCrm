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
        Schema::create('evt_template_discipline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('discipline_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('template_id')->references('id')->on('evt_discipline_templates')->onDelete('cascade');
            $table->foreign('discipline_id')->references('id')->on('evt_disciplines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_template_discipline');
    }
};
