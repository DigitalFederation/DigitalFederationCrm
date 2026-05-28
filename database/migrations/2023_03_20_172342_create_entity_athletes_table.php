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
        Schema::create('entity_athletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('entity');
            $table->foreignUuid('individual_id')->constrained('individual');
            $table->foreignId('sport_id')->constrained('sports');
            $table->string('entity_name')->nullable();
            $table->string('individual_name')->nullable();
            $table->string('sport_name')->nullable();
            $table->text('status_class');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_athletes');
    }
};
