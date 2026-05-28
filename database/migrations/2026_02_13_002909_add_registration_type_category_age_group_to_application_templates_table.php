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
        Schema::table('application_templates', function (Blueprint $table) {
            $table->string('registration_type')->nullable()->after('event_category');
            $table->string('category')->nullable()->after('registration_type');
            $table->string('age_group')->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_templates', function (Blueprint $table) {
            $table->dropColumn(['registration_type', 'category', 'age_group']);
        });
    }
};
