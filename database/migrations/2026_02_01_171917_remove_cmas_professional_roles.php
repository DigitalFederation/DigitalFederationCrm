<?php

use Domain\Individuals\Models\ProfessionalRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $codesToRemove = [
            'BOD_MEMBER',
            'CMAS_EUROPE_MEMBER',
            'CMAS_ASIA_MEMBER',
            'CMAS_OCEANIA_MEMBER',
            'CMAS_AMERICA_MEMBER',
            'CMAS_BOD_MEMBER',
        ];

        // Get the IDs of the professional roles to remove
        $roleIds = ProfessionalRole::whereIn('code', $codesToRemove)->pluck('id');

        // First, delete related pivot table records
        DB::table('federation_professional_role')
            ->whereIn('professional_role_id', $roleIds)
            ->delete();

        // Then delete the professional roles
        ProfessionalRole::whereIn('code', $codesToRemove)->delete();
    }

    public function down(): void
    {
        $rolesToRestore = [
            ['code' => 'BOD_MEMBER', 'name' => 'CMAS HQ', 'role' => 'STAFF'],
            ['code' => 'CMAS_EUROPE_MEMBER', 'name' => 'CMAS Europe member', 'role' => 'STAFF'],
            ['code' => 'CMAS_ASIA_MEMBER', 'name' => 'CMAS Asia member', 'role' => 'STAFF'],
            ['code' => 'CMAS_OCEANIA_MEMBER', 'name' => 'CMAS Oceania member', 'role' => 'STAFF'],
            ['code' => 'CMAS_AMERICA_MEMBER', 'name' => 'CMAS America member', 'role' => 'STAFF'],
            ['code' => 'CMAS_BOD_MEMBER', 'name' => 'CMAS Bod Member', 'role' => 'STAFF'],
        ];

        foreach ($rolesToRestore as $role) {
            ProfessionalRole::create($role);
        }
    }
};
