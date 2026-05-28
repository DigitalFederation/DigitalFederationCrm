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
        Schema::create('evt_attribute_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('evt_attributes');
            $table->string('operator');
            $table->string('default_value');
            $table->string('comparison_field')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_attribute_rules');
    }
};
