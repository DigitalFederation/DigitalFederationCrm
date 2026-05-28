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
            $table->boolean('requires_official_documents')->default(false)->after('allow_entity_group_request');
            $table->json('required_document_types')->nullable()->after('requires_official_documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license', function (Blueprint $table) {
            $table->dropColumn(['requires_official_documents', 'required_document_types']);
        });
    }
};
