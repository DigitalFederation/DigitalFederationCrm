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
        Schema::create('evt_federation_candidacy_winners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('federation_candidacy_id');
            $table->unsignedBigInteger('event_id');
            $table->timestamps();

            $table->foreign('federation_candidacy_id')->references('id')->on('evt_federation_candidacies');
            $table->foreign('event_id')->references('id')->on('evt_events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_federation_candidacy_winners');
    }
};
