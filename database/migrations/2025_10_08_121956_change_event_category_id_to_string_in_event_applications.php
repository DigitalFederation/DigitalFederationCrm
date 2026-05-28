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
        // Only rename if event_category_id exists (for databases created before the schema was updated)
        if (Schema::hasColumn('event_applications', 'event_category_id')) {
            Schema::table('event_applications', function (Blueprint $table) {
                $table->renameColumn('event_category_id', 'event_category');
            });
        }

        // Only change column type if event_category exists
        if (Schema::hasColumn('event_applications', 'event_category')) {
            Schema::table('event_applications', function (Blueprint $table) {
                $table->string('event_category')->nullable()->change();
            });
        }

        // Only rename if event_category_id exists (for databases created before the schema was updated)
        if (Schema::hasColumn('application_templates', 'event_category_id')) {
            Schema::table('application_templates', function (Blueprint $table) {
                $table->renameColumn('event_category_id', 'event_category');
            });
        }

        // Only change column type if event_category exists
        if (Schema::hasColumn('application_templates', 'event_category')) {
            Schema::table('application_templates', function (Blueprint $table) {
                $table->string('event_category')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only rename back if event_category exists and event_category_id doesn't
        if (Schema::hasColumn('event_applications', 'event_category') && ! Schema::hasColumn('event_applications', 'event_category_id')) {
            Schema::table('event_applications', function (Blueprint $table) {
                $table->renameColumn('event_category', 'event_category_id');
            });

            Schema::table('event_applications', function (Blueprint $table) {
                $table->unsignedBigInteger('event_category_id')->nullable()->change();
            });
        }

        if (Schema::hasColumn('application_templates', 'event_category') && ! Schema::hasColumn('application_templates', 'event_category_id')) {
            Schema::table('application_templates', function (Blueprint $table) {
                $table->renameColumn('event_category', 'event_category_id');
            });

            Schema::table('application_templates', function (Blueprint $table) {
                $table->unsignedBigInteger('event_category_id')->nullable()->change();
            });
        }
    }
};
