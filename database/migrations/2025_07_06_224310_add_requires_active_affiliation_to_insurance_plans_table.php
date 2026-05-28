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
        Schema::table('insurance_plans', function (Blueprint $table) {
            $table->boolean('requires_active_affiliation')->default(true)->after('required_document_type')
                ->comment('Whether this insurance requires an active affiliation to be purchased');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance_plans', function (Blueprint $table) {
            $table->dropColumn('requires_active_affiliation');
        });
    }
};
