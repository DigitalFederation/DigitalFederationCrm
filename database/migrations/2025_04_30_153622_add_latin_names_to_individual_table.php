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
        Schema::table('individual', function (Blueprint $table) {
            // Add columns after 'surname'
            $table->string('first_name_latin')->nullable()->after('surname')->comment('Given Name transliterated to Latin characters');
            $table->string('last_name_latin')->nullable()->after('first_name_latin')->comment('Surname transliterated to Latin characters');

            // Add indexes for faster searching
            $table->index('first_name_latin');
            $table->index('last_name_latin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['first_name_latin']);
            $table->dropIndex(['last_name_latin']);

            // Drop columns
            $table->dropColumn('first_name_latin');
            $table->dropColumn('last_name_latin');
        });
    }
};
