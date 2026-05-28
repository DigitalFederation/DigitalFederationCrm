<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Default role name mappings (lowercase slug -> role name).
     */
    private array $roleNameMappings = [
        'instructor' => 'individual-instructor',
        'coach' => 'individual-coach',
        'athlete' => 'individual-athlete',
        'technical_official' => 'individual-technical-official',
        'leader' => 'individual-leader',
        'diver' => 'individual-diver',
        'diving-instructor' => 'individual-diving-instructor',
        'divingprofessional' => 'individual-diving-pro',
        'instructor-trainer' => 'individual-instructor-trainer',
        'coach-trainer' => 'individual-coach-trainer',
    ];

    /**
     * Committee-specific primary role overrides.
     */
    private array $committeePrimaryRoleOverrides = [
        'DIVING' => [
            'instructor' => 'individual-cmas-pro',
            'instructor-trainer' => 'individual-cmas-pro',
            'diver' => 'individual-cmas-diver',
            'leader' => 'individual-cmas-diver',
        ],
        'SCIENTIFIC' => [
            'instructor' => 'individual-cmas-pro',
            'instructor-trainer' => 'individual-cmas-pro',
            'diver' => 'individual-cmas-diver',
        ],
    ];

    public function up(): void
    {
        // Find all certifications with a professional role but no certification_roles entry
        $missing = DB::table('certification as c')
            ->join('professional_roles as pr', 'c.professional_role_id', '=', 'pr.id')
            ->leftJoin('committee as cm', 'c.committee_id', '=', 'cm.id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('certification_roles as cr')
                    ->whereColumn('cr.certification_id', 'c.id');
            })
            ->select('c.id as certification_id', 'pr.role as prof_role', 'cm.code as committee_code')
            ->get();

        if ($missing->isEmpty()) {
            Log::info('No missing certification_roles entries found.');

            return;
        }

        Log::info("Found {$missing->count()} certifications missing role mappings.");

        // Pre-load all role IDs by name
        $roleIdsByName = DB::table('roles')
            ->where('guard_name', 'web')
            ->pluck('id', 'name');

        $inserted = 0;

        foreach ($missing as $row) {
            $slug = strtolower($row->prof_role);
            $committeeCode = $row->committee_code;

            // Resolve role name: committee override first, then default mapping
            $roleName = $this->resolvePrimaryRoleName($slug, $committeeCode);

            // Get or create the role
            $roleId = $roleIdsByName[$roleName] ?? null;

            if (! $roleId) {
                // Create the role if it doesn't exist
                $roleId = DB::table('roles')->insertGetId([
                    'name' => $roleName,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $roleIdsByName[$roleName] = $roleId;

                Log::info("Created missing role: {$roleName}");
            }

            DB::table('certification_roles')->insertOrIgnore([
                'certification_id' => $row->certification_id,
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $inserted++;
        }

        Log::info("Populated {$inserted} missing certification_roles entries.");
    }

    public function down(): void
    {
        // No rollback needed - the entries should exist
    }

    private function resolvePrimaryRoleName(string $professionalRoleSlug, ?string $committeeCode): string
    {
        if ($committeeCode && isset($this->committeePrimaryRoleOverrides[$committeeCode][$professionalRoleSlug])) {
            return $this->committeePrimaryRoleOverrides[$committeeCode][$professionalRoleSlug];
        }

        return $this->roleNameMappings[$professionalRoleSlug] ?? 'individual-' . $professionalRoleSlug;
    }
};
