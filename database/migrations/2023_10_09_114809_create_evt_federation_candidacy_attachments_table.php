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
        Schema::create('evt_federation_candidacy_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidacy_id');
            $table->string('file_name');
            $table->string('mime_type');
            $table->string('disk');
            $table->unsignedInteger('size');
            $table->unsignedInteger('order_column')->nullable();
            $table->timestamps();

            $table->foreign('candidacy_id')->references('id')->on('evt_federation_candidacies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_federation_candidacy_attachments');
    }
};
