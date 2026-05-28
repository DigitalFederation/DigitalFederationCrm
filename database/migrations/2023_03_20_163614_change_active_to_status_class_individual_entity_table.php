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
        Schema::table('individual_entity', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->text('status_class')->after('individual_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_entity', function (Blueprint $table) {
            $table->dropColumn('status_class');
            $table->boolean('active')->nullable();
        });
    }
};
