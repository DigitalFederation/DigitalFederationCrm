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
        Schema::create('evt_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('attribute_type');
            $table->string('default_value')->nullable();
            $table->string('validation_rules')->nullable();
            $table->string('custom_class')->nullable();
            $table->string('fillable_type');
            $table->boolean('fillable_global');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_attributes');
    }
};
