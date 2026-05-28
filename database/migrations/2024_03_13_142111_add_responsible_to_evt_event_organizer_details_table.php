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
        Schema::table('evt_event_organizer_details', function (Blueprint $table) {
            $table->string('responsible_person')->nullable();
            // email contact
            $table->string('email_contact')->nullable()->after('responsible_person');
            // phone contact
            $table->string('phone_contact')->nullable()->after('email_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_event_organizer_details', function (Blueprint $table) {
            $table->dropColumn('responsible_person');
            $table->dropColumn('email_contact');
            $table->dropColumn('phone_contact');
        });
    }
};
