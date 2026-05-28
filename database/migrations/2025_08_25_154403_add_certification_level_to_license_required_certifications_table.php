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
        Schema::table('license_required_certifications', function (Blueprint $table) {
            // Add certification_level column to support national certification level requirements
            $table->string('certification_level')->nullable()->after('requester_type');

            // Drop the old unique constraint
            $table->dropUnique('lic_req_cert_unique');

            // Add new unique constraint including certification_level
            $table->unique(['license_id', 'certification_id', 'requester_type', 'certification_level'], 'lic_req_cert_level_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_required_certifications', function (Blueprint $table) {
            // Drop new unique constraint
            $table->dropUnique('lic_req_cert_level_unique');

            // Drop certification_level column
            $table->dropColumn('certification_level');

            // Restore the old unique constraint
            $table->unique(['license_id', 'certification_id', 'requester_type'], 'lic_req_cert_unique');
        });
    }
};
