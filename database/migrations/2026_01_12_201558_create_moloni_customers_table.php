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
        Schema::create('moloni_customers', function (Blueprint $table) {
            $table->id();
            $table->morphs('customerable');
            $table->unsignedInteger('moloni_customer_id');
            $table->string('moloni_vat', 20)->nullable();
            $table->string('moloni_name', 255)->nullable();
            $table->timestamps();

            $table->unique(['customerable_type', 'customerable_id']);
            $table->index('moloni_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moloni_customers');
    }
};
