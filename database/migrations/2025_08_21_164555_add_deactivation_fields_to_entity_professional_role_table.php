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
        Schema::table('entity_professional_role', function (Blueprint $table) {
            // Add deactivation fields if they don't exist
            if (! Schema::hasColumn('entity_professional_role', 'deactivated_at')) {
                $table->timestamp('deactivated_at')->nullable()->after('status_class');
            }
            if (! Schema::hasColumn('entity_professional_role', 'deactivation_reason')) {
                $table->text('deactivation_reason')->nullable()->after('deactivated_at');
            }
            if (! Schema::hasColumn('entity_professional_role', 'deactivated_by')) {
                $table->string('deactivated_by')->nullable()->after('deactivation_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entity_professional_role', function (Blueprint $table) {
            $table->dropColumn(['deactivated_at', 'deactivation_reason', 'deactivated_by']);
        });
    }
};
