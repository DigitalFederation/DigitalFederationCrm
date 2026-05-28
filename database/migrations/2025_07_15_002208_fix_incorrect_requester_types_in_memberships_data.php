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
        // Fix member_subscriptions table
        $this->fixMemberSubscriptions();

        // Fix affiliations table
        $this->fixAffiliations();

        // Fix insurances table
        $this->fixInsurances();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration fixes data, so we don't reverse it
        // as it would restore incorrect data
    }

    private function fixMemberSubscriptions(): void
    {
        // Update member_subscriptions where requester_type is App\Models\User
        // Set requester_type based on member_type and requester_id to member_id
        DB::statement("
            UPDATE member_subscriptions ms
            SET 
                requester_type = CASE 
                    WHEN ms.member_type = 'entity' THEN 'entity'
                    WHEN ms.member_type = 'individual' THEN 'individual'
                    ELSE ms.requester_type
                END,
                requester_id = ms.member_id
            WHERE ms.requester_type = 'App\\\\Models\\\\User'
        ");
    }

    private function fixAffiliations(): void
    {
        // Update affiliations where requester_type is App\Models\User
        DB::statement("
            UPDATE affiliations a
            SET 
                requester_type = CASE 
                    WHEN a.member_type = 'entity' THEN 'entity'
                    WHEN a.member_type = 'individual' THEN 'individual'
                    ELSE a.requester_type
                END,
                requester_id = a.member_id
            WHERE a.requester_type = 'App\\\\Models\\\\User'
        ");
    }

    private function fixInsurances(): void
    {
        // Update insurances where requester_type is App\Models\User
        DB::statement("
            UPDATE insurances i
            SET 
                requester_type = CASE 
                    WHEN i.member_type = 'entity' THEN 'entity'
                    WHEN i.member_type = 'individual' THEN 'individual'
                    ELSE i.requester_type
                END,
                requester_id = i.member_id
            WHERE i.requester_type = 'App\\\\Models\\\\User'
        ");
    }
};
