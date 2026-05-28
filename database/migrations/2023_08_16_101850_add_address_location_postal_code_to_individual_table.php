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
            $table->string('address')->nullable()->after('doc_ref');
            $table->string('location')->nullable()->after('address');
            $table->string('postal_code')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('location');
            $table->dropColumn('postal_code');
        });
    }
};
