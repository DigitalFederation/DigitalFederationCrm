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
        Schema::create('evt_federation_candidacy_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidacy_id');
            $table->unsignedBigInteger('attribute_id');
            $table->string('value');
            $table->timestamps();

            $table->foreign('candidacy_id')->references('id')->on('evt_federation_candidacies');
            $table->foreign('attribute_id')->references('id')->on('evt_attributes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_federation_candidacy_attributes');
    }
};
