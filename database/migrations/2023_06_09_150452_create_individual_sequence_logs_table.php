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
        Schema::create('individual_sequence_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('individual_id')->unique()->constrained('individual');
            $table->unsignedBigInteger('log_number')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_diving_logs');
    }
};
