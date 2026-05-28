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
        Schema::table('evt_attributes', function (Blueprint $table) {
            $table->json('attribute_data')->nullable(); // Adding attribute_data column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_attributes', function (Blueprint $table) {
            $table->dropColumn('attribute_data'); // Dropping attribute_data column
        });
    }
};
