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
        Schema::table('districts', function (Blueprint $table) {
            // First, drop the foreign key constraint
            $table->dropForeign(['country_id']);

            // Make country_id nullable
            $table->bigInteger('country_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Make country_id not nullable again (will fail if there are null values)
        Schema::table('districts', function (Blueprint $table) {
            $table->bigInteger('country_id')->nullable(false)->change();

            // Re-add the foreign key constraint
            $table->foreign('country_id')->references('id')->on('country')->onDelete('cascade');
        });
    }
};
