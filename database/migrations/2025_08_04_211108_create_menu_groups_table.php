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
        Schema::create('menu_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->string('name')->comment('Display name for the group');
            $table->string('machine_name')->comment('Machine-readable name');
            $table->text('description')->nullable()->comment('Optional description');
            $table->string('icon')->nullable()->comment('Optional icon for the group');
            $table->integer('order')->default(0)->comment('Display order');
            $table->boolean('is_default')->default(false)->comment('Whether this is the default group');
            $table->boolean('active')->default(true)->comment('Whether this group is active');
            $table->timestamps();

            // Indexes for performance
            $table->index(['menu_id', 'machine_name']);
            $table->index(['menu_id', 'active']);
            $table->index(['menu_id', 'order']);

            // Ensure unique machine names per menu
            $table->unique(['menu_id', 'machine_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_groups');
    }
};
