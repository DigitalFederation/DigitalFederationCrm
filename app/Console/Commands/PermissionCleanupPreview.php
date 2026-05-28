<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionCleanupPreview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:cleanup-preview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preview which orphaned permissions would be deleted by the cleanup migration';

    /**
     * Permissions to check (same as migration)
     */
    protected array $orphanedPermissions = [
        // Hyphenated duplicates (17)
        'access-certifications',
        'access-diving-certifications-attributed',
        'access-diving-location',
        'access-documents',
        'access-entities',
        'access-events',
        'access-federation-official-documents',
        'access-federations',
        'access-individuals',
        'access-licenses',
        'access-memberships',
        'access-official-documents',
        'access-products',
        'access-scientific-certifications-attributed',
        'access-settings',
        'access-sport-certifications-attributed',
        'access-users',

        // Unused CRUD hyphenated permissions (9)
        'create-entities',
        'create-individuals',
        'create-payment-documents',
        'delete-entities',
        'delete-individuals',
        'edit-entities',
        'edit-individuals',
        'impersonate-users',
        'manage-user-roles',

        // Unused manage hyphenated permissions (7)
        'manage-certifications',
        'manage-diving-locations',
        'manage-events',
        'manage-federations',
        'manage-licenses',
        'manage-memberships',
        'manage-products',
        'manage-settings',
        'manage-users',

        // Legacy/unused permissions (3)
        'access documents invoices',
        'download individual official documents',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Checking orphaned permissions...');
        $this->newLine();

        $toDelete = [];
        $toSkip = [];
        $notFound = [];

        foreach ($this->orphanedPermissions as $permissionName) {
            $result = $this->checkPermission($permissionName);

            if ($result['status'] === 'delete') {
                $toDelete[] = $result;
            } elseif ($result['status'] === 'skip') {
                $toSkip[] = $result;
            } else {
                $notFound[] = $result;
            }
        }

        $this->displayResults($toDelete, $toSkip, $notFound);

        return Command::SUCCESS;
    }

    /**
     * Check if permission can be safely deleted
     */
    protected function checkPermission(string $permissionName): array
    {
        $permission = Permission::where('name', $permissionName)->first();

        if (! $permission) {
            return [
                'name' => $permissionName,
                'status' => 'not_found',
                'reason' => 'Permission does not exist',
            ];
        }

        // Check role assignments
        $roleCount = DB::table('role_has_permissions')
            ->where('permission_id', $permission->id)
            ->count();

        if ($roleCount > 0) {
            return [
                'name' => $permissionName,
                'status' => 'skip',
                'reason' => "Has {$roleCount} role assignment(s)",
            ];
        }

        // Check direct user assignments
        $userCount = DB::table('model_has_permissions')
            ->where('permission_id', $permission->id)
            ->count();

        if ($userCount > 0) {
            return [
                'name' => $permissionName,
                'status' => 'skip',
                'reason' => "Has {$userCount} direct user assignment(s)",
            ];
        }

        // Check menu items
        $menuItemsCount = DB::table('menu_items')
            ->whereRaw('JSON_CONTAINS(permissions, ?)', [json_encode($permissionName)])
            ->count();

        if ($menuItemsCount > 0) {
            return [
                'name' => $permissionName,
                'status' => 'skip',
                'reason' => "Used in {$menuItemsCount} menu item(s)",
            ];
        }

        // Safe to delete
        return [
            'name' => $permissionName,
            'status' => 'delete',
            'reason' => 'No assignments or references found',
        ];
    }

    /**
     * Display results in a formatted way
     */
    protected function displayResults(array $toDelete, array $toSkip, array $notFound): void
    {
        // Display permissions to be deleted
        if (! empty($toDelete)) {
            $this->info('✅ SAFE TO DELETE (' . count($toDelete) . ' permissions):');
            $this->newLine();

            $tableData = [];
            foreach ($toDelete as $item) {
                $tableData[] = [
                    $item['name'],
                    $item['reason'],
                ];
            }

            $this->table(['Permission Name', 'Status'], $tableData);
            $this->newLine();
        }

        // Display permissions to skip
        if (! empty($toSkip)) {
            $this->warn('⚠️  WILL BE SKIPPED (' . count($toSkip) . ' permissions):');
            $this->newLine();

            $tableData = [];
            foreach ($toSkip as $item) {
                $tableData[] = [
                    $item['name'],
                    $item['reason'],
                ];
            }

            $this->table(['Permission Name', 'Reason'], $tableData);
            $this->newLine();
        }

        // Display permissions not found
        if (! empty($notFound)) {
            $this->comment('ℹ️  NOT FOUND (' . count($notFound) . ' permissions):');
            $this->newLine();

            foreach ($notFound as $item) {
                $this->line("  • {$item['name']}");
            }
            $this->newLine();
        }

        // Summary
        $this->info('📊 SUMMARY:');
        $this->line('  Total checked: ' . count($this->orphanedPermissions));
        $this->line('  <info>Safe to delete: ' . count($toDelete) . '</info>');
        $this->line('  <comment>Will skip: ' . count($toSkip) . '</comment>');
        $this->line('  <fg=gray>Not found: ' . count($notFound) . '</>');
        $this->newLine();

        if (count($toDelete) > 0) {
            $this->info('✨ To proceed with deletion, run:');
            $this->line('   <fg=cyan>php artisan migrate</>');
            $this->newLine();
            $this->comment('💡 The migration can be rolled back if needed with:');
            $this->line('   <fg=yellow>php artisan migrate:rollback</>');
        } else {
            $this->info('✨ All permissions are either in use or already deleted.');
        }

        $this->newLine();
    }
}
