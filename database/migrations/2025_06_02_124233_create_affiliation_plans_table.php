<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliation_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('federation_id')->constrained('federation')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_months');
            $table->decimal('base_fee', 10, 2);
            $table->enum('type', ['individual', 'entity']);
            $table->decimal('individual_fee', 10, 2)->nullable();
            $table->decimal('entity_fee', 10, 2)->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliation_plans');
    }
};
