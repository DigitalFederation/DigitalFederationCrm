<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fix: customerable_id was created as bigint via morphs(), but Individual
     * uses UUIDs (char(36)). This caused UUID truncation and customer collisions
     * in Moloni invoicing. Change to char(36) to support both UUID and integer IDs.
     */
    public function up(): void
    {
        // Clear corrupted cache - records will be re-created on next invoice generation
        DB::table('moloni_customers')->truncate();

        Schema::table('moloni_customers', function (Blueprint $table) {
            $table->dropUnique(['customerable_type', 'customerable_id']);
            $table->dropIndex(['customerable_type', 'customerable_id']);
        });

        Schema::table('moloni_customers', function (Blueprint $table) {
            $table->char('customerable_id', 36)->change();
        });

        Schema::table('moloni_customers', function (Blueprint $table) {
            $table->unique(['customerable_type', 'customerable_id']);
            $table->index(['customerable_type', 'customerable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('moloni_customers')->truncate();

        Schema::table('moloni_customers', function (Blueprint $table) {
            $table->dropUnique(['customerable_type', 'customerable_id']);
            $table->dropIndex(['customerable_type', 'customerable_id']);
        });

        Schema::table('moloni_customers', function (Blueprint $table) {
            $table->unsignedBigInteger('customerable_id')->change();
        });

        Schema::table('moloni_customers', function (Blueprint $table) {
            $table->unique(['customerable_type', 'customerable_id']);
            $table->index(['customerable_type', 'customerable_id']);
        });
    }
};
