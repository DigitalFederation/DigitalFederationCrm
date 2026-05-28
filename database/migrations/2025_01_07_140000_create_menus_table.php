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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Display name, e.g., "CMAS Admin Menu"');
            $table->string('machine_name')->unique()->comment('Machine name, e.g., "cmas"');
            $table->text('description')->nullable()->comment('Optional description of the menu');
            $table->boolean('active')->default(true)->comment('Whether this menu is currently active');
            $table->json('metadata')->nullable()->comment('Additional metadata for the menu');
            $table->timestamps();

            $table->index(['machine_name', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
