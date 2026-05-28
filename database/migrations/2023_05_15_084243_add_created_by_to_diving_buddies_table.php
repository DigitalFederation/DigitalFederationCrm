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
        Schema::table('diving_buddies', function (Blueprint $table) {
            // Add created_by and modified_by columns
            $table->char('created_by', 36)->nullable()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->char('updated_by', 36)->nullable()->index();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_buddies', function (Blueprint $table) {
            $table->dropForeign('diving_buddies_created_by_foreign');
            $table->dropIndex('diving_buddies_created_by_index');
            $table->dropColumn('created_by');
            $table->dropForeign('diving_buddies_updated_by_foreign');
            $table->dropIndex('diving_buddies_updated_by_index');
            $table->dropColumn('updated_by');
        });
    }
};
