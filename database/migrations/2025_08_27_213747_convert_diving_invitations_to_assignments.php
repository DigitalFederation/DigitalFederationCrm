<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert invitation status classes to assignment status classes
        DB::table('diving_entity_technical_directors')
            ->where('status_class', 'Domain\Diving\States\AcceptedDivingTechnicalDirectorInvitationState')
            ->update(['status_class' => 'Domain\Diving\States\AssignedDivingTechnicalDirectorState']);

        // Remove pending invitations as they don't make sense in assignment context
        DB::table('diving_entity_technical_directors')
            ->where('status_class', 'Domain\Diving\States\PendingDivingTechnicalDirectorInvitationState')
            ->delete();

        // Convert rejected invitations to removed assignments if any exist
        DB::table('diving_entity_technical_directors')
            ->where('status_class', 'Domain\Diving\States\RejectedDivingTechnicalDirectorInvitationState')
            ->update(['status_class' => 'Domain\Diving\States\RemovedDivingTechnicalDirectorState']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert assignment status classes back to invitation status classes
        DB::table('diving_entity_technical_directors')
            ->where('status_class', 'Domain\Diving\States\AssignedDivingTechnicalDirectorState')
            ->update(['status_class' => 'Domain\Diving\States\AcceptedDivingTechnicalDirectorInvitationState']);

        DB::table('diving_entity_technical_directors')
            ->where('status_class', 'Domain\Diving\States\RemovedDivingTechnicalDirectorState')
            ->update(['status_class' => 'Domain\Diving\States\RejectedDivingTechnicalDirectorInvitationState']);
    }
};
