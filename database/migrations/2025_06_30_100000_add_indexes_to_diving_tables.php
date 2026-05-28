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
        // Add indexes to diving_professional_certifications for better query performance
        Schema::table('diving_professional_certifications', function (Blueprint $table) {
            $table->index(['individual_id', 'status_class'], 'dpc_individual_status_idx');
            $table->index('certification_system', 'dpc_cert_system_idx');
            $table->index('status_class', 'dpc_status_idx');
        });

        // Add indexes to diving_technical_director_invitations for better query performance
        Schema::table('diving_technical_director_invitations', function (Blueprint $table) {
            $table->index(['individual_id', 'status_class'], 'dtdi_individual_status_idx');
            $table->index(['entity_id', 'status_class'], 'dtdi_entity_status_idx');
            $table->index('license_attributed_id', 'dtdi_license_attr_idx');
            $table->index('status_class', 'dtdi_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_professional_certifications', function (Blueprint $table) {
            $table->dropIndex('dpc_individual_status_idx');
            $table->dropIndex('dpc_cert_system_idx');
            $table->dropIndex('dpc_status_idx');
        });

        Schema::table('diving_technical_director_invitations', function (Blueprint $table) {
            $table->dropIndex('dtdi_individual_status_idx');
            $table->dropIndex('dtdi_entity_status_idx');
            $table->dropIndex('dtdi_license_attr_idx');
            $table->dropIndex('dtdi_status_idx');
        });
    }
};
