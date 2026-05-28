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
        // Check if column already exists before adding it
        if (! Schema::hasColumn('diving_professional_certifications', 'document_type')) {
            Schema::table('diving_professional_certifications', function (Blueprint $table) {
                $table->enum('document_type', [
                    'medical_statement',
                    'professional_insurance',
                    'other',
                ])->nullable()->after('certification_system');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_professional_certifications', function (Blueprint $table) {
            $table->dropColumn('document_type');
        });
    }
};
