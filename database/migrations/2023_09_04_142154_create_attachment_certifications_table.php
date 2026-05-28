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
        Schema::create('attachment_certifications', function (Blueprint $table) {
            $table->foreignId('attachment_id')->constrained('attachments')->cascadeOnDelete();
            $table->foreignId('certification_id')->constrained('certification')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachment_certifications');
    }
};
