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
        Schema::table('permissions', function (Blueprint $table) {
            // Check if columns don't already exist
            if (! Schema::hasColumn('permissions', 'category')) {
                $table->string('category', 100)->nullable()->after('guard_name');
                $table->index('category');
            }

            if (! Schema::hasColumn('permissions', 'description')) {
                $table->text('description')->nullable()->after('category');
            }

            if (! Schema::hasColumn('permissions', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('description');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }

            if (! Schema::hasColumn('permissions', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });

        // Add updated_by to route_permissions if it doesn't exist
        Schema::table('route_permissions', function (Blueprint $table) {
            if (! Schema::hasColumn('route_permissions', 'updated_by')) {
                $table->char('updated_by', 36)->nullable()->after('created_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'category')) {
                $table->dropIndex(['category']);
                $table->dropColumn('category');
            }

            if (Schema::hasColumn('permissions', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('permissions', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }

            if (Schema::hasColumn('permissions', 'updated_by')) {
                $table->dropForeign(['updated_by']);
                $table->dropColumn('updated_by');
            }
        });

        Schema::table('route_permissions', function (Blueprint $table) {
            if (Schema::hasColumn('route_permissions', 'updated_by')) {
                $table->dropForeign(['updated_by']);
                $table->dropColumn('updated_by');
            }
        });
    }
};
