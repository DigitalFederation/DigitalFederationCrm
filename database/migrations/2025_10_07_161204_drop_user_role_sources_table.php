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
        Schema::dropIfExists('user_role_sources');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the table if migration is rolled back
        Schema::create('user_role_sources', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 36);
            $table->unsignedBigInteger('role_id');
            $table->string('source_type', 50);
            $table->string('source_id', 255)->nullable();
            $table->timestamp('assigned_at');
            $table->char('assigned_by', 36)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'role_id']);
            $table->index(['source_type', 'source_id']);
            $table->unique(['user_id', 'role_id', 'source_type', 'source_id'], 'unique_user_role_source');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
