<?php

namespace App\Console\Commands;

use App\Models\Committee;
use Domain\Individuals\Models\ProfessionalRole;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AnalyzeRoleMappings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:role-mappings 
                            {--export : Export results to CSV file}
                            {--detailed : Show detailed information about each mapping}
                            {--unmapped : Show only unmapped professional roles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze ProfessionalRole to permission role mappings';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Analyzing ProfessionalRole to Permission Role Mappings...');
        $this->line('');

        // Collect all mapping data
        $mappings = collect();

        // 1. Analyze basic professional role mappings
        $this->analyzeProfessionalRoleMappings($mappings);

        // 2. Analyze committee-specific mappings
        $this->analyzeCommitteeSpecificMappings($mappings);

        // 3. Analyze view role mappings
        $this->analyzeViewRoleMappings($mappings);

        // 4. Analyze federation-based roles
        $this->analyzeFederationBasedRoles($mappings);

        // 5. Analyze hardcoded role name mappings
        $this->analyzeHardcodedMappings($mappings);

        // 6. Display results
        $this->displayResults($mappings);

        // 7. Export if requested
        if ($this->option('export')) {
            $this->exportToCSV($mappings);
        }

        $this->line('');
        $this->info('Analysis complete!');

        return Command::SUCCESS;
    }

    /**
     * Analyze basic professional role mappings
     */
    private function analyzeProfessionalRoleMappings(Collection &$mappings): void
    {
        $this->info('1. Analyzing Basic Professional Role Mappings...');

        $professionalRoles = ProfessionalRole::with(['committee', 'licenses', 'certifications'])->get();

        $basicRoleMapping = [
            'INSTRUCTOR' => 'individual-instructor',
            'COACH' => 'individual-coach',
            'ATHLETE' => 'individual-athlete',
            'TECHNICAL_OFFICIAL' => 'individual-technical-official',
            'LEADER' => 'individual-leader',
            'DIVER' => 'individual-diver',
        ];

        foreach ($professionalRoles as $role) {
            $baseMapping = $basicRoleMapping[strtoupper($role->role)] ?? null;

            $mappings->push([
                'professional_role_id' => $role->id,
                'professional_role_name' => $role->name,
                'professional_role_code' => $role->code,
                'professional_role_type' => $role->role,
                'committee' => $role->committee?->name ?? 'N/A',
                'committee_code' => $role->committee?->code ?? 'N/A',
                'permission_role' => $baseMapping ?? 'Not mapped',
                'mapping_type' => 'Basic',
                'licenses_count' => $role->licenses->count(),
                'certifications_count' => $role->certifications->count(),
            ]);
        }

        $this->info('  Found ' . $professionalRoles->count() . ' professional roles');
    }

    /**
     * Analyze committee-specific mappings
     */
    private function analyzeCommitteeSpecificMappings(Collection &$mappings): void
    {
        $this->info('2. Analyzing Committee-Specific Mappings...');

        $committees = Committee::with(['professionalRoles.licenses', 'professionalRoles.certifications'])->get();

        $committeeSpecificMappings = [
            'DIVING' => [
                'INSTRUCTOR' => 'view-individual-diving-instructor',
                'LEADER' => 'view-individual-diving-leader',
            ],
            'SCIENTIFIC' => [
                'INSTRUCTOR' => 'view-individual-scientific-instructor',
                'LEADER' => 'view-individual-scientific-leader',
            ],
        ];

        foreach ($committees as $committee) {
            if (isset($committeeSpecificMappings[$committee->code])) {
                foreach ($committee->professionalRoles as $role) {
                    if (isset($committeeSpecificMappings[$committee->code][$role->role])) {
                        $mappings->push([
                            'professional_role_id' => $role->id,
                            'professional_role_name' => $role->name,
                            'professional_role_code' => $role->code,
                            'professional_role_type' => $role->role,
                            'committee' => $committee->name,
                            'committee_code' => $committee->code,
                            'permission_role' => $committeeSpecificMappings[$committee->code][$role->role],
                            'mapping_type' => 'Committee-Specific',
                            'licenses_count' => $role->licenses->count(),
                            'certifications_count' => $role->certifications->count(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Analyze view role mappings
     */
    private function analyzeViewRoleMappings(Collection &$mappings): void
    {
        $this->info('3. Analyzing View Role Mappings...');

        $viewRoles = [
            'COACH' => 'view-individual-coach',
            'TECHNICAL_OFFICIAL' => 'view-individual-technical-official',
        ];

        foreach ($viewRoles as $roleType => $permissionRole) {
            $roles = ProfessionalRole::where('role', $roleType)->with(['committee', 'licenses', 'certifications'])->get();

            foreach ($roles as $role) {
                $mappings->push([
                    'professional_role_id' => $role->id,
                    'professional_role_name' => $role->name,
                    'professional_role_code' => $role->code,
                    'professional_role_type' => $role->role,
                    'committee' => $role->committee?->name ?? 'N/A',
                    'committee_code' => $role->committee?->code ?? 'N/A',
                    'permission_role' => $permissionRole,
                    'mapping_type' => 'View Role',
                    'licenses_count' => $role->licenses->count(),
                    'certifications_count' => $role->certifications->count(),
                ]);
            }
        }
    }

    /**
     * Analyze federation-based roles
     */
    private function analyzeFederationBasedRoles(Collection &$mappings): void
    {
        $this->info('4. Analyzing Federation-Based Roles...');

        // This is a special role that doesn't directly map to ProfessionalRole
        $mappings->push([
            'professional_role_id' => 'N/A',
            'professional_role_name' => 'Federation Approved Individual',
            'professional_role_code' => 'N/A',
            'professional_role_type' => 'FEDERATION_BASED',
            'committee' => 'N/A',
            'committee_code' => 'N/A',
            'permission_role' => 'individual-approved',
            'mapping_type' => 'Federation-Based',
            'licenses_count' => 0,
            'certifications_count' => 0,
        ]);
    }

    /**
     * Analyze hardcoded role name mappings from SyncUserRolesAction
     */
    private function analyzeHardcodedMappings(Collection &$mappings): void
    {
        $this->info('5. Analyzing Hardcoded Role Name Mappings...');

        // These are the hardcoded mappings from SyncUserRolesAction
        $hardcodedMappings = [
            'individual_approved' => 'individual-approved',
            'instructor' => 'individual-instructor',
            'coach' => 'individual-coach',
            'athlete' => 'individual-athlete',
            'technical_official' => 'individual-technical-official',
            'leader' => 'individual-leader',
            'diver' => 'individual-diver',
            'diving' => 'individual-diver',
            'scientific' => 'individual-scientific',
            'sport' => 'individual-sport',
            'view_diving_instructor' => 'view-individual-diving-instructor',
            'view_scientific_instructor' => 'view-individual-scientific-instructor',
            'view_diving_leader' => 'view-individual-diving-leader',
            'view_scientific_leader' => 'view-individual-scientific-leader',
            'view_coach' => 'view-individual-coach',
            'view_technical_official' => 'view-individual-technical-official',
        ];

        $this->line('');
        $this->info('Hardcoded mapping array in SyncUserRolesAction:');
        foreach ($hardcodedMappings as $key => $value) {
            $this->line("  '$key' => '$value'");
        }
        $this->line('');
    }

    /**
     * Display results in table format
     */
    private function displayResults(Collection $mappings): void
    {
        $this->line('');
        $this->info('=== ROLE MAPPING ANALYSIS RESULTS ===');
        $this->line('');

        // Filter for unmapped roles if option is set
        if ($this->option('unmapped')) {
            $mappings = $mappings->where('permission_role', 'Not mapped');
            $this->warn('Showing only unmapped professional roles');
            $this->line('');
        }

        // Group by mapping type
        $groupedMappings = $mappings->groupBy('mapping_type');

        foreach ($groupedMappings as $type => $typeMappings) {
            $this->info("[$type Mappings]");

            $headers = ['ID', 'Name', 'Type', 'Committee', 'Permission Role'];

            if ($this->option('detailed')) {
                $headers = array_merge($headers, ['Code', 'Licenses', 'Certifications']);
            }

            $this->table(
                $headers,
                $typeMappings->map(function ($mapping) {
                    $row = [
                        $mapping['professional_role_id'],
                        $mapping['professional_role_name'],
                        $mapping['professional_role_type'],
                        $mapping['committee_code'],
                        $mapping['permission_role'],
                    ];

                    if ($this->option('detailed')) {
                        $row[] = $mapping['professional_role_code'];
                        $row[] = $mapping['licenses_count'];
                        $row[] = $mapping['certifications_count'];
                    }

                    return $row;
                })->toArray()
            );
            $this->line('');
        }

        // Summary statistics
        $this->info('=== SUMMARY STATISTICS ===');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Professional Roles', ProfessionalRole::count()],
                ['Total Committees', Committee::count()],
                ['Total Unique Permission Roles', $mappings->pluck('permission_role')->unique()->filter()->count()],
                ['Professional Roles with Licenses', ProfessionalRole::has('licenses')->count()],
                ['Professional Roles with Certifications', ProfessionalRole::has('certifications')->count()],
                ['Unmapped Professional Roles', $mappings->where('permission_role', 'Not mapped')->count()],
            ]
        );

        // Show detailed role breakdown if requested
        if ($this->option('detailed')) {
            $this->line('');
            $this->info('=== PERMISSION ROLE BREAKDOWN ===');
            $roleBreakdown = $mappings->groupBy('permission_role')->map(function ($group, $role) {
                return [
                    'role' => $role,
                    'count' => $group->count(),
                    'professional_roles' => $group->pluck('professional_role_name')->unique()->implode(', '),
                ];
            });

            $this->table(
                ['Permission Role', 'Count', 'Professional Roles'],
                $roleBreakdown->map(function ($item) {
                    return [
                        $item['role'],
                        $item['count'],
                        \Illuminate\Support\Str::limit($item['professional_roles'], 50),
                    ];
                })->toArray()
            );
        }
    }

    /**
     * Export results to CSV
     */
    private function exportToCSV(Collection $mappings): void
    {
        $filename = 'role_mappings_analysis_' . now()->format('Y-m-d_His') . '.csv';
        $path = storage_path('app/exports/' . $filename);

        // Ensure export directory exists
        if (! is_dir(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        // Open file for writing
        $file = fopen($path, 'w');

        // Add headers
        fputcsv($file, [
            'Professional Role ID',
            'Professional Role Name',
            'Professional Role Code',
            'Professional Role Type',
            'Committee',
            'Committee Code',
            'Permission Role',
            'Mapping Type',
            'Licenses Count',
            'Certifications Count',
        ]);

        // Add data
        foreach ($mappings as $mapping) {
            fputcsv($file, [
                $mapping['professional_role_id'],
                $mapping['professional_role_name'],
                $mapping['professional_role_code'],
                $mapping['professional_role_type'],
                $mapping['committee'],
                $mapping['committee_code'],
                $mapping['permission_role'],
                $mapping['mapping_type'],
                $mapping['licenses_count'],
                $mapping['certifications_count'],
            ]);
        }

        fclose($file);

        $this->line('');
        $this->info('Results exported to: ' . $path);
    }
}
