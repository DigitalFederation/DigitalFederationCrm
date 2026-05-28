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
        Schema::table('certification', function (Blueprint $table) {
            $table->string('certification_category')->nullable()->after('committee_id');
            $table->string('certification_view')->nullable()->after('certification_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification', function (Blueprint $table) {
            $table->dropColumn('certification_category');
            $table->dropColumn('certification_view');
        });
    }
};
