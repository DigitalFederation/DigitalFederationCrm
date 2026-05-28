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
        Schema::table('federation', function (Blueprint $table) {
            $table->string('category')->nullable()->after('is_local');
            $table->boolean('is_manual')->default(false)->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('federation', function (Blueprint $table) {
            $table->dropColumn(['category', 'is_manual']);
        });
    }
};
