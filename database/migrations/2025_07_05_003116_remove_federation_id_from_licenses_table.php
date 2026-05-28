<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Remove federation_id from licenses table as we now use
     * a many-to-many relationship through federation_licenses pivot table.
     */
    public function up(): void
    {
        Schema::table('license', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['federation_id']);

            // Drop the index
            $table->dropIndex(['federation_id']);

            // Drop the column
            $table->dropColumn('federation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license', function (Blueprint $table) {
            // Re-add the column
            $table->unsignedBigInteger('federation_id')->nullable()->after('committee_id');

            // Re-add the foreign key constraint
            $table->foreign('federation_id')->references('id')->on('federation')->onDelete('cascade');

            // Re-add the index
            $table->index('federation_id');
        });
    }
};
