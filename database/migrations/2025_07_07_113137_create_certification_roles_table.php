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
        Schema::create('certification_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained('certification')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('committee_id')->nullable()->constrained('committee')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint on combination of certification_id, role_id, and committee_id
            $table->unique(['certification_id', 'role_id', 'committee_id'], 'certification_role_committee_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_roles');
    }
};
