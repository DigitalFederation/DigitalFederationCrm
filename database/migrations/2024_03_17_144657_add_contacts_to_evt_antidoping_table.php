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
        Schema::table('evt_antidoping', function (Blueprint $table) {
            // Event ID
            $table->unsignedBigInteger('event_id')->after('id');
            // Responsible Name
            $table->string('responsible_name', 100)->nullable()->after('event_id');
            // Responsible Email
            $table->string('responsible_email', 100)->nullable()->after('responsible_name');
            // Responsible Phone
            $table->string('responsible_phone', 20)->nullable()->after('responsible_email');
            // Total expected athletes
            $table->integer('expected_athletes')->nullable()->after('responsible_phone');
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_antidoping', function (Blueprint $table) {
            $table->dropColumn('responsible_name');
            $table->dropColumn('responsible_email');
            $table->dropColumn('responsible_phone');
            $table->dropColumn('expected_athletes');
            $table->dropTimestamps();
        });
    }
};
