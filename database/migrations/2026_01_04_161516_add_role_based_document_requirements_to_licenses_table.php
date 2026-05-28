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
            $table->json('required_athlete_documents')->nullable()->after('required_document_types');
            $table->json('required_coach_documents')->nullable()->after('required_athlete_documents');
            $table->json('required_official_documents')->nullable()->after('required_coach_documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license', function (Blueprint $table) {
            $table->dropColumn([
                'required_athlete_documents',
                'required_coach_documents',
                'required_official_documents',
            ]);
        });
    }
};
