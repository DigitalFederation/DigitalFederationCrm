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
        Schema::create('federation_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('federation_id')->nullable()->constrained('federation')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->boolean('requires_active_membership')->default(true);
            $table->timestamps();

            // Unique constraint on combination of federation_id and role_id
            $table->unique(['federation_id', 'role_id'], 'federation_role_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('federation_roles');
    }
};
