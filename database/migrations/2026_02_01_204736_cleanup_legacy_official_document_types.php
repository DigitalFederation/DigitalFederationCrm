<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Converts legacy official document types to their new equivalents:
     * - InstructorLeaderCodeOfConduct -> DivingProfessionalCodeOfConduct
     * - InsuranceInstructorLeader -> ProfessionalLiabilityInsurance
     * - InsuranceCoach -> ProfessionalLiabilityInsurance
     * - InsuranceRefereeJudge -> ProfessionalLiabilityInsurance
     * - CmasResponsibleDiver -> DELETE
     * - InsuranceDiver -> DELETE
     * - Passport -> DELETE
     * - InsuranceTeamOfficial -> DELETE
     */
    public function up(): void
    {
        // Convert InstructorLeaderCodeOfConduct to DivingProfessionalCodeOfConduct
        DB::table('official_documents')
            ->where('type', 'InstructorLeaderCodeOfConduct')
            ->update(['type' => 'DivingProfessionalCodeOfConduct']);

        // Convert insurance types to ProfessionalLiabilityInsurance
        DB::table('official_documents')
            ->whereIn('type', ['InsuranceInstructorLeader', 'InsuranceCoach', 'InsuranceRefereeJudge'])
            ->update(['type' => 'ProfessionalLiabilityInsurance']);

        // Mark deprecated document types as archived by prefixing the type
        // This preserves the data for auditing instead of hard-deleting
        $deprecatedTypes = ['CmasResponsibleDiver', 'InsuranceDiver', 'Passport', 'InsuranceTeamOfficial'];

        foreach ($deprecatedTypes as $type) {
            DB::table('official_documents')
                ->where('type', $type)
                ->update(['type' => 'ARCHIVED_' . $type]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * Note: Type conversions are not fully reversible since multiple old types
     * were consolidated into single new types.
     */
    public function down(): void
    {
        // Restore archived document types
        $deprecatedTypes = ['CmasResponsibleDiver', 'InsuranceDiver', 'Passport', 'InsuranceTeamOfficial'];

        foreach ($deprecatedTypes as $type) {
            DB::table('official_documents')
                ->where('type', 'ARCHIVED_' . $type)
                ->update(['type' => $type]);
        }

        // Convert DivingProfessionalCodeOfConduct back to InstructorLeaderCodeOfConduct
        // Note: This will affect ALL DivingProfessionalCodeOfConduct documents,
        // not just the ones that were converted
        DB::table('official_documents')
            ->where('type', 'DivingProfessionalCodeOfConduct')
            ->update(['type' => 'InstructorLeaderCodeOfConduct']);
    }
};
