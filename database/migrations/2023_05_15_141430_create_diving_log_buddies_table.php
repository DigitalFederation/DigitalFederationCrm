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
        Schema::create('diving_log_buddies', function (Blueprint $table) {
            $table->id();
            // id of the buddy
            $table->foreignId('diving_buddy_id')->constrained()->onDelete('cascade');
            // id of the diving log
            $table->foreignId('diving_log_id')->constrained()->on('diving_log')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diving_log_buddies');
    }
};
