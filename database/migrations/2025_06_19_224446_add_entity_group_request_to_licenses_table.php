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
            $table->boolean('allow_entity_group_request')->default(false)->after('requester_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license', function (Blueprint $table) {
            $table->dropColumn('allow_entity_group_request');
        });
    }
};
