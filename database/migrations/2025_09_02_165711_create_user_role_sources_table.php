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
        Schema::create('user_role_sources', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 36); // UUID to match users table
            $table->unsignedBigInteger('role_id');
            $table->string('source_type', 50); // 'license', 'manual', 'federation', 'certification'
            $table->string('source_id', 255)->nullable(); // ID of the license/federation/etc. (can be UUID or integer)
            $table->timestamp('assigned_at');
            $table->char('assigned_by', 36)->nullable(); // User who assigned (for manual assignments)
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'role_id']);
            $table->index(['source_type', 'source_id']);
            $table->unique(['user_id', 'role_id', 'source_type', 'source_id'], 'unique_user_role_source');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role_sources');
    }
};
