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
        // Update evt_athletes_enrollment table
        Schema::table('evt_athletes_enrollment', function (Blueprint $table) {

            // Check if the foreign keys exist before attempting to drop them
            if (DB::getSchemaBuilder()->hasColumn('evt_athletes_enrollment', 'enrollment_id')) {
                $table->dropForeign(['enrollment_id']);
            }
            if (DB::getSchemaBuilder()->hasColumn('evt_athletes_enrollment', 'event_id')) {
                $table->dropForeign(['event_id']);
            }
            if (DB::getSchemaBuilder()->hasColumn('evt_athletes_enrollment', 'discipline_id')) {
                $table->dropForeign(['discipline_id']);
            }
            if (DB::getSchemaBuilder()->hasColumn('evt_athletes_enrollment', 'federation_id')) {
                $table->dropForeign(['federation_id']);
            }

            $table->foreign('enrollment_id')->references('id')->on('evt_enrollments')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('evt_events')->onDelete('cascade');
            $table->foreign('discipline_id')->references('id')->on('evt_disciplines')->onDelete('cascade');
            $table->foreign('federation_id')->references('id')->on('federation')->onDelete('cascade');
        });

        // Update evt_coaches_enrollment table
        Schema::table('evt_coaches_enrollment', function (Blueprint $table) {

            if (DB::getSchemaBuilder()->hasColumn('evt_coaches_enrollment', 'enrollment_id')) {
                $table->dropForeign(['enrollment_id']);
            }
            if (DB::getSchemaBuilder()->hasColumn('evt_coaches_enrollment', 'event_id')) {
                $table->dropForeign(['event_id']);
            }
            if (DB::getSchemaBuilder()->hasColumn('evt_coaches_enrollment', 'federation_id')) {
                $table->dropForeign(['federation_id']);
            }

            $table->foreign('enrollment_id')->references('id')->on('evt_enrollments')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('evt_events')->onDelete('cascade');
            $table->foreign('federation_id')->references('id')->on('federation')->onDelete('cascade');
        });

        // Update evt_individuals_enrollment table
        Schema::table('evt_individuals_enrollment', function (Blueprint $table) {
            if (DB::getSchemaBuilder()->hasColumn('evt_individuals_enrollment', 'enrollment_id')) {
                $table->dropForeign(['enrollment_id']);
            }
            if (DB::getSchemaBuilder()->hasColumn('evt_individuals_enrollment', 'event_id')) {
                $table->dropForeign(['event_id']);
            }
            if (DB::getSchemaBuilder()->hasColumn('evt_individuals_enrollment', 'federation_id')) {
                $table->dropForeign(['federation_id']);
            }

            $table->foreign('enrollment_id')->references('id')->on('evt_enrollments')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('evt_events')->onDelete('cascade');
            $table->foreign('federation_id')->references('id')->on('federation')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert changes made in the up() method
        Schema::table('evt_athletes_enrollment', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropForeign(['event_id']);
            $table->dropForeign(['discipline_id']);
            $table->dropForeign(['federation_id']);

            $table->foreign('enrollment_id')->references('id')->on('evt_enrollments');
            $table->foreign('event_id')->references('id')->on('evt_events');
            $table->foreign('discipline_id')->references('id')->on('evt_disciplines');
            $table->foreign('federation_id')->references('id')->on('federation');
        });

        Schema::table('evt_coaches_enrollment', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropForeign(['event_id']);
            $table->dropForeign(['federation_id']);

            $table->foreign('enrollment_id')->references('id')->on('evt_enrollments');
            $table->foreign('event_id')->references('id')->on('evt_events');
            $table->foreign('federation_id')->references('id')->on('federation');
        });

        Schema::table('evt_individuals_enrollment', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropForeign(['event_id']);
            $table->dropForeign(['federation_id']);

            $table->foreign('enrollment_id')->references('id')->on('evt_enrollments');
            $table->foreign('event_id')->references('id')->on('evt_events');
            $table->foreign('federation_id')->references('id')->on('federation');
        });
    }
};
