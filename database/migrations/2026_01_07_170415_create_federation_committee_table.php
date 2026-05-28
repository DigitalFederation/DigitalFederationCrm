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
        Schema::create('federation_committee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('federation_id')
                ->constrained('federation')
                ->cascadeOnDelete();
            $table->foreignId('committee_id')
                ->constrained('committee')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['federation_id', 'committee_id']);
            $table->index('federation_id');
            $table->index('committee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('federation_committee');
    }
};
