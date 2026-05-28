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
        Schema::create('evt_discipline_attribute_association', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained('evt_disciplines');
            $table->foreignId('attribute_id')->constrained('evt_attributes');
            $table->string('custom_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_discipline_attribute_association');
    }
};
