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
        Schema::create('entity_coach', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('entity');
            $table->foreignUuid('coach_id')->constrained('individual');
            $table->string('entity_name')->nullable();
            $table->string('coach_name')->nullable();
            $table->text('status_class');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_coach');
    }
};
