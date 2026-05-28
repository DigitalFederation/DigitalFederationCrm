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
        Schema::dropIfExists('entity_instructor');
        Schema::dropIfExists('entity_coach');

        Schema::create('entity_professional_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('entity');
            $table->foreignUuid('individual_id')->constrained('individual');
            $table->foreignId('professional_role_id')->constrained('professional_roles');
            $table->string('entity_name')->nullable();
            $table->string('individual_name')->nullable();
            $table->string('role_name')->nullable();
            $table->text('status_class');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_professional_role');
    }
};
