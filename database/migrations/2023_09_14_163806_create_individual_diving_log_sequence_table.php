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
        Schema::create('individual_diving_log_sequence', function (Blueprint $table) {
            $table->id();
            $table->uuid('individual_id');
            $table->integer('log_number')->default(0);
            $table->unsignedBigInteger('diving_log_id')->nullable();  // Nullable to accommodate logs yet to be created

            $table->timestamps();

            $table->foreign('individual_id')->references('id')->on('individual')->onDelete('cascade');
            $table->foreign('diving_log_id')->references('id')->on('diving_log')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_diving_log_sequence');
    }
};
