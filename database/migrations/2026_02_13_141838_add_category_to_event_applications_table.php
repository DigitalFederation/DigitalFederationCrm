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
        Schema::table('event_applications', function (Blueprint $table) {
            $table->string('category', 1)->nullable()->after('event_category');
        });
    }

    public function down(): void
    {
        Schema::table('event_applications', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
