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
        Schema::table('license', function (Blueprint $table) {
            $table->unsignedBigInteger('federation_id')->nullable()->after('committee_id');
            $table->foreign('federation_id')->references('id')->on('federation')->onDelete('cascade');
            $table->index('federation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license', function (Blueprint $table) {
            $table->dropForeign(['federation_id']);
            $table->dropIndex(['federation_id']);
            $table->dropColumn('federation_id');
        });
    }
};
