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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            $table->unsignedBigInteger('committee_id')->nullable()->comment('Committee ID (no foreign key constraint)');

            // Display properties
            $table->string('name')->comment('Translation key, e.g., "menu.cmas.dashboard"');
            $table->string('icon')->nullable()->comment('Heroicon name, e.g., "chart-bar"');
            $table->integer('order')->default(0)->comment('Display order within parent');

            // Route configuration
            $table->string('route_name')->nullable()->comment('Laravel named route');
            $table->json('route_parameters')->nullable()->comment('Route parameters as JSON');
            $table->json('active_patterns')->nullable()->comment('URL patterns that mark this item active');

            // Permission & visibility
            $table->json('permissions')->nullable()->comment('Array of required permissions');
            $table->json('visibility_conditions')->nullable()->comment('Complex visibility rules');
            $table->boolean('visible')->default(true)->comment('Base visibility flag');

            // Advanced features
            $table->json('badge_config')->nullable()->comment('Badge/count display configuration');
            $table->string('translation_namespace')->nullable()->comment('Translation namespace override');
            $table->json('metadata')->nullable()->comment('Additional metadata');

            // Audit fields
            $table->timestamps();

            // Indexes for performance
            $table->index(['menu_id', 'parent_id', 'order']);
            $table->index(['menu_id', 'visible']);
            $table->index(['route_name']);
            $table->index(['committee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
