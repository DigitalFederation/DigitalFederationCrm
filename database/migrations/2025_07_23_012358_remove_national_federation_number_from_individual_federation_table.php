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
        Schema::table('individual_federation', function (Blueprint $table) {
            $table->dropColumn('national_federation_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_federation', function (Blueprint $table) {
            $table->string('national_federation_number')->nullable()->after('individual_id');
        });
    }
};
