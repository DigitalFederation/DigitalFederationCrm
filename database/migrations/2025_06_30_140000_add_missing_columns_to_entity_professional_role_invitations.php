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
        // Only add columns if they don't exist
        Schema::table('entity_professional_role_invitations', function (Blueprint $table) {
            if (! Schema::hasColumn('entity_professional_role_invitations', 'entity_id')) {
                $table->foreignId('entity_id')->after('id')->nullable()->constrained('entity')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('entity_professional_role_invitations', 'individual_id')) {
                $table->char('individual_id', 36)->after('entity_id')->nullable();
                $table->foreign('individual_id', 'epri_individual_fk')->references('id')->on('individual')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('entity_professional_role_invitations', 'professional_role_id')) {
                $table->unsignedBigInteger('professional_role_id')->after('individual_id')->nullable();
                $table->foreign('professional_role_id', 'epri_prof_role_fk')->references('id')->on('professional_roles')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('entity_professional_role_invitations', 'status_class')) {
                $table->string('status_class')->after('professional_role_id')->nullable();
            }

            if (! Schema::hasColumn('entity_professional_role_invitations', 'message')) {
                $table->text('message')->nullable()->after('status_class');
            }
        });

        // Skip the nullable to required changes to avoid foreign key constraint issues
        // The controller already handles nullable values appropriately
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entity_professional_role_invitations', function (Blueprint $table) {
            if (Schema::hasColumn('entity_professional_role_invitations', 'message')) {
                $table->dropColumn('message');
            }
            if (Schema::hasColumn('entity_professional_role_invitations', 'status_class')) {
                $table->dropColumn('status_class');
            }
            if (Schema::hasColumn('entity_professional_role_invitations', 'professional_role_id')) {
                $table->dropForeign('epri_prof_role_fk');
                $table->dropColumn('professional_role_id');
            }
            if (Schema::hasColumn('entity_professional_role_invitations', 'individual_id')) {
                $table->dropForeign('epri_individual_fk');
                $table->dropColumn('individual_id');
            }
            if (Schema::hasColumn('entity_professional_role_invitations', 'entity_id')) {
                $table->dropForeign(['entity_id']);
                $table->dropColumn('entity_id');
            }
        });
    }
};
