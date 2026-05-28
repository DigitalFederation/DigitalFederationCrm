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
        Schema::create('evt_age_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('min_age');
            $table->integer('max_age');
            $table->text('comments')->nullable();
            $table->foreignId('discipline_id')->constrained('evt_disciplines');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_age_categories');
    }
};
