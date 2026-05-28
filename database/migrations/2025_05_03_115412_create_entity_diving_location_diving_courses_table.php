<?php

declare(strict_types=1);

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
        Schema::create('entity_diving_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('entity')->cascadeOnDelete();
            $table->foreignId('certification_id')->constrained('certification')->cascadeOnDelete();
            $table->date('start_date')->nullable();
            $table->text('about')->nullable();
            $table->timestamps();

            // Unique constraint per entity, certification, and start date
            $table->unique(['entity_id', 'certification_id', 'start_date'], 'entity_cert_start_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_diving_courses');
    }
};
