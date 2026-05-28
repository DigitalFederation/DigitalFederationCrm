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
            $table->string('national_federation_number')->nullable()->after('member_number');
            $table->index('national_federation_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual', function (Blueprint $table) {
            $table->dropIndex(['national_federation_number']);
            $table->dropColumn('national_federation_number');
        });
    }
};
