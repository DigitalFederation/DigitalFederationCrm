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
        Schema::table('entity', function (Blueprint $table) {
            $table->unsignedBigInteger('member_number')->nullable()->unique()->after('code_cmas');
            $table->index('member_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entity', function (Blueprint $table) {
            $table->dropIndex(['member_number']);
            $table->dropColumn('member_number');
        });
    }
};
