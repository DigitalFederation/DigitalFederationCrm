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
        Schema::create('evt_event_attribute', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('attribute_id');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('evt_events')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('evt_attributes')->onDelete('cascade');

            $table->primary(['event_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_event_attribute');
    }
};
