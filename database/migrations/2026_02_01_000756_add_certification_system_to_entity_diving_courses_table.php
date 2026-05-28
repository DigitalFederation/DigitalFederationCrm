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
        if (! Schema::hasColumn('entity_diving_courses', 'certification_system')) {
            Schema::table('entity_diving_courses', function (Blueprint $table) {
                $table->string('certification_system')->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('entity_diving_courses', 'certification_system')) {
            Schema::table('entity_diving_courses', function (Blueprint $table) {
                $table->dropColumn('certification_system');
            });
        }
    }
};
