<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class CompleteCmasMenuSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->command) {
            $this->command->info('Syncing menu structure from config/menu.php...');
        }

        // Process all menus from config
        $menuConfig = config('menu', []);

        if (empty($menuConfig)) {
            if ($this->command) {
                $this->command->error('No menu configuration found in config/menu.php');
            }

            return;
        }

        $totalCreated = 0;
        $totalSkipped = 0;

        foreach ($menuConfig as $menuMachineName => $menuItems) {
            // Get or create the menu
            $menu = Menu::firstOrCreate(
                ['machine_name' => $menuMachineName],
                [
                    'name' => $this->getMenuDisplayName($menuMachineName),
                    'description' => $this->getMenuDescription($menuMachineName),
                    'visible' => true,
                    'order' => $this->getMenuOrder($menuMachineName),
                ]
            );

            if ($this->command) {
                $this->command->info("Processing {$menuMachineName} menu...");
            }

            // Create menu items
            $order = 10;
            foreach ($menuItems as $menuItem) {
                $result = $this->createOrUpdateMenuItem($menu->id, null, $menuItem, $order);
                $totalCreated += $result['created'];
                $totalSkipped += $result['skipped'];
                $order += 10;
            }
        }

        if ($this->command) {
            $this->command->info('Menu sync completed successfully.');
            $this->command->info("Created: {$totalCreated} menu items");
            $this->command->info("Skipped (already exist): {$totalSkipped} menu items");
            $this->command->info('Total menu items in database: ' . MenuItem::count());
        }
    }

    private function createOrUpdateMenuItem(int $menuId, ?int $parentId, array $config, int $order): array
    {
        $created = 0;
        $skipped = 0;

        // Extract basic properties
        $name = $this->getMenuName($config);
        $icon = $config['icon'] ?? null;
        $route = $this->getRoute($config);
        $activePatterns = $this->getActivePatterns($config);
        $permissions = $this->getPermissions($config);
        $committeeId = $this->getCommitteeId($config);

        // Check if menu item already exists
        $existingItem = MenuItem::where('menu_id', $menuId)
            ->where('parent_id', $parentId)
            ->where('name', $name)
            ->first();

        if ($existingItem) {
            $skipped++;
            $menuItem = $existingItem;

            if ($this->command) {
                $this->command->line("  - Skipped existing: {$name}");
            }
        } else {
            // Create the menu item
            $menuItem = MenuItem::create([
                'menu_id' => $menuId,
                'parent_id' => $parentId,
                'name' => $name,
                'icon' => $icon,
                'route_name' => $route['name'] ?? null,
                'route_parameters' => ! empty($route['parameters']) ? $route['parameters'] : null,
                'active_patterns' => $activePatterns,
                'permissions' => $permissions,
                'visibility_conditions' => null,
                'order' => $order,
                'visible' => true,
                'committee_id' => $committeeId,
                'metadata' => [
                    'migrated_from_config' => true,
                    'original_config' => $config,
                ],
            ]);

            $created++;

            if ($this->command) {
                $this->command->line("  + Created: {$name}");
            }
        }

        // Process children if they exist
        if (! empty($config['children'])) {
            $childOrder = 10;
            foreach ($config['children'] as $child) {
                $childResult = $this->createOrUpdateMenuItem($menuId, $menuItem->id, $child, $childOrder);
                $created += $childResult['created'];
                $skipped += $childResult['skipped'];
                $childOrder += 10;
            }
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    private function getMenuDisplayName(string $machineName): string
    {
        $names = [
            'cmas' => 'CMAS Admin Menu',
            'federation' => 'Federation Menu',
            'entity' => 'Entity Menu',
            'individual' => 'Individual Menu',
        ];

        return $names[$machineName] ?? ucfirst($machineName) . ' Menu';
    }

    private function getMenuDescription(string $machineName): string
    {
        $descriptions = [
            'cmas' => 'Main administrative menu for CMAS system',
            'federation' => 'Navigation menu for federation users',
            'entity' => 'Navigation menu for entity administrators',
            'individual' => 'Navigation menu for individual users',
        ];

        return $descriptions[$machineName] ?? "Navigation menu for {$machineName} users";
    }

    private function getMenuOrder(string $machineName): int
    {
        $orders = [
            'cmas' => 1,
            'federation' => 2,
            'entity' => 3,
            'individual' => 4,
        ];

        return $orders[$machineName] ?? 99;
    }

    private function getMenuName(array $config): string
    {
        $name = $config['name'] ?? 'Unknown';
        $internationalName = config('branding.international.name', 'International Federation');

        // Convert translation keys to Portuguese text
        $translations = [
            // CMAS Menu
            'menu.cmas.dashboard' => 'Painel',
            'menu.cmas.federation' => 'Federações',
            'menu.cmas.national_federations' => 'Federações Nacionais',
            'menu.cmas.local_organizations' => 'Organizações Locais',
            'menu.cmas.memberships' => 'Filiações',
            'menu.cmas.membership_packages' => 'Pacotes de Filiação',
            'menu.cmas.affiliation_plans' => 'Planos de Filiação',
            'menu.cmas.insurance_plans' => 'Planos de Seguro',
            'menu.cmas.insurances' => 'Seguros',
            'menu.cmas.member_subscriptions' => 'Subscrições de Membros',
            'menu.cmas.affiliations' => 'Afiliações',
            'menu.cmas.entities' => 'Entidades',
            'menu.cmas.individuals' => 'Indivíduos',
            'menu.cmas.events' => 'Eventos',
            'menu.cmas.events_list' => 'Lista de Eventos',
            'menu.cmas.event_attributes' => 'Atributos de Eventos',
            'menu.cmas.certifications' => 'Certificações',
            'menu.cmas.diving_scientific_manager' => 'Gestor Mergulho/Científico',
            'menu.cmas.sport_manager' => 'Gestor Desportivo',
            'menu.cmas.diving_certifications' => 'Certificações de Mergulho',
            'menu.cmas.scientific_certifications' => 'Certificações Científicas',
            'menu.cmas.sport_certifications' => 'Certificações Desportivas',
            'menu.cmas.licenses' => 'Licenças',
            'menu.cmas.license_manager' => 'Gestor de Licenças',
            'menu.cmas.diving_entities' => 'Entidades de Mergulho',
            'menu.cmas.scientific_entities' => 'Entidades Científicas',
            'menu.cmas.sport_entities' => 'Entidades Desportivas',
            'menu.cmas.diving_individuals' => 'Indivíduos de Mergulho',
            'menu.cmas.scientific_individuals' => 'Indivíduos Científicos',
            'menu.cmas.sport_individuals' => 'Indivíduos Desportivos',
            'menu.cmas.diving_services' => 'Serviços de Mergulho',
            'menu.cmas.diving_license_validation' => 'Validação de Licenças de Mergulho',
            'menu.cmas.diving_professional_certifications' => 'Certificações Profissionais de Mergulho',
            'menu.cmas.diving_professionals_list' => 'Lista de Profissionais de Mergulho',
            'menu.cmas.users' => 'Utilizadores',
            'menu.cmas.latest_users' => 'Últimos Utilizadores',
            'menu.cmas.role_management' => 'Gestão de Funções',
            'menu.cmas.permission_management' => 'Gestão de Permissões',
            'menu.cmas.route_permissions' => 'Permissões de Rotas',
            'menu.cmas.role_mappings' => 'Mapeamentos de Funções',
            'menu.cmas.payments' => 'Pagamentos',
            'menu.cmas.documents' => 'Documentos',
            'menu.cmas.invoices' => 'Faturas',
            'menu.cmas.files_area' => 'Área de Ficheiros',
            'menu.cmas.administrative' => 'Administrativo',
            'menu.cmas.sport' => 'Desporto',
            'menu.cmas.diving' => 'Mergulho',
            'menu.cmas.scientific' => 'Científico',
            'menu.cmas.legal_documents' => 'Documentos Legais',
            'menu.cmas.settings' => 'Definições',
            'menu.cmas.reports' => 'Relatórios',
            'menu.cmas.statistics' => 'Estatísticas',
            'menu.cmas.shipping' => 'Envios',
            'menu.cmas.products' => 'Produtos',
            'menu.cmas.staff_roles' => 'Funções de Staff',
            'menu.cmas.member_number_settings' => 'Definições de Números de Membro',
            'menu.cmas.diving_courses_entities' => 'Cursos de Mergulho - Entidades',
            'menu.cmas.districts' => 'Distritos',
            'menu.cmas.zones' => 'Zonas',

            // Federation Menu
            'menu.federation.dashboard' => 'Painel',
            'menu.federation.memberships' => 'Filiações',
            'menu.federation.members' => 'Membros',
            'menu.federation.individuals' => 'Indivíduos',
            'menu.federation.entities' => 'Entidades',
            'menu.federation.sport' => 'Desporto',
            'menu.federation.diving' => 'Mergulho',
            'menu.federation.scientific' => 'Científico',
            'menu.federation.certifications' => 'Certificações',
            'menu.federation.clubs' => 'Clubes',
            'menu.federation.athletes' => 'Atletas',
            'menu.federation.coaches' => 'Treinadores',
            'menu.federation.referees_judges' => 'Árbitros/Juízes',
            'menu.federation.instructor_leaders' => 'Instrutores/Líderes',
            'menu.federation.official_documents' => 'Documentos Oficiais',
            'menu.federation.logbook' => 'Logbook',
            'menu.federation.diving_spots' => 'Locais de Mergulho',
            'menu.federation.dives' => 'Mergulhos',
            'menu.federation.events' => 'Eventos',
            'menu.federation.candidatures' => 'Candidaturas',
            'menu.federation.registration' => 'Registo',
            'menu.federation.files_area' => 'Área de Ficheiros',
            'menu.federation.administrative' => 'Administrativo',
            'menu.federation.payments' => 'Pagamentos',

            // Entity Menu
            'menu.entity.dashboard' => 'Painel',
            'menu.entity.entity_plans' => 'Planos da Entidade',
            'menu.entity.members_plans' => 'Planos dos Membros',
            'menu.entity.insurance_plans' => 'Planos de Seguro',
            'menu.entity.affiliation_plans' => 'Planos de Filiação',
            'menu.entity.members' => 'Membros',
            'menu.entity.federation_organizations' => 'Organizações da Federação',
            'menu.entity.sport' => 'Desporto',
            'menu.entity.club_licenses' => 'Licenças do Clube',
            'menu.entity.athlete_licenses' => 'Licenças de Atletas',
            'menu.entity.coach_licenses' => 'Licenças de Treinadores',
            'menu.entity.diving_services' => 'Serviços de Mergulho',
            'menu.entity.service_provider_licenses' => 'Licenças de Prestadores de Serviços',
            'menu.entity.diving_professionals' => 'Profissionais de Mergulho',
            'menu.entity.international_federation' => $internationalName,
            'menu.entity.entity_licenses' => 'Licenças da Entidade',
            'menu.entity.professional_licenses' => 'Licenças Profissionais',
            'menu.entity.certifications' => 'Certificações',
            'menu.entity.licenses' => 'Licenças',
            'menu.entity.purchase_entity_license' => 'Comprar Licença da Entidade',
            'menu.entity.purchase_member_licenses' => 'Comprar Licenças de Membros',
            'menu.entity.events' => 'Eventos',
            'menu.entity.registration' => 'Registo',
            'menu.entity.files_area' => 'Área de Ficheiros',
            'menu.entity.administrative' => 'Administrativo',
            'menu.entity.diving' => 'Mergulho',
            'menu.entity.scientific' => 'Científico',
            'menu.entity.sport' => 'Desporto',
            'menu.entity.official_documents' => 'Documentos Oficiais',
            'menu.entity.payments' => 'Pagamentos',

            // Individual Menu
            'menu.individual.dashboard' => 'Painel',
            'menu.individual.federation_organization' => 'Organização da Federação',
            'menu.individual.entities' => 'Entidades',
            'menu.individual.affiliations' => 'Afiliações',
            'menu.individual.insurances' => 'Seguros',
            'menu.individual.diving_professional' => 'Profissional de Mergulho',
            'menu.individual.diving_certifications' => 'Certificações de Mergulho',
            'menu.individual.diving_official_documents' => 'Documentos Oficiais de Mergulho',
            'menu.individual.technical_director' => 'Director Técnico',
            'menu.individual.diving_entities' => 'Entidades de Mergulho',
            'menu.individual.my_certifications' => 'Minhas Certificações',
            'menu.individual.cards' => 'Cartões',
            'menu.individual.diving' => 'Mergulho',
            'menu.individual.scientific' => 'Científico',
            'menu.individual.sport' => 'Desporto',
            'menu.individual.my_licenses' => 'Minhas Licenças',
            'menu.individual.personal_documents' => 'Documentos Pessoais',
            'menu.individual.diver' => 'Mergulhador',
            'menu.individual.instructor_leader' => 'Instrutor/Líder',
            'menu.individual.coach' => 'Treinador',
            'menu.individual.referee_judge' => 'Árbitro/Juiz',
            'menu.individual.athlete' => 'Atleta',
            'menu.individual.clubs' => 'Clubes',
            'menu.individual.club_requests' => 'Pedidos de Clubes',
            'menu.individual.clubs_requests' => 'Pedidos de Clubes',
            'menu.individual.certifications_to_approve' => 'Certificações para Aprovar',
            'menu.individual.issued_certifications' => 'Certificações Emitidas',
            'menu.individual.dives_to_approve' => 'Mergulhos para Aprovar',
            'menu.individual.scientific_entities' => 'Entidades Científicas',
            'menu.individual.events' => 'Eventos',
            'menu.individual.files_area' => 'Área de Ficheiros',
            'menu.individual.administrative' => 'Administrativo',
            'menu.individual.payments' => 'Pagamentos',
        ];

        return $translations[$name] ?? $name;
    }

    private function getRoute(array $config): array
    {
        if (empty($config['route'])) {
            return [];
        }

        if (is_string($config['route'])) {
            return ['name' => $config['route']];
        }

        if (is_array($config['route'])) {
            if (isset($config['route'][0])) {
                return [
                    'name' => $config['route'][0],
                    'parameters' => $config['route'][1] ?? null,
                ];
            }
        }

        return [];
    }

    private function getActivePatterns(array $config): array
    {
        return $config['active'] ?? [];
    }

    private function getPermissions(array $config): array
    {
        if (empty($config['can'])) {
            return [];
        }

        if (is_string($config['can'])) {
            return [$config['can']];
        }

        if (is_array($config['can'])) {
            return $config['can'];
        }

        return [];
    }

    private function getCommitteeId(array $config): ?int
    {
        if (empty($config['committee'])) {
            return null;
        }

        // Map committee names to IDs
        $committeeMap = [
            'sport' => 1,
            'scientific' => 2,
            'diving' => 3,
        ];

        return $committeeMap[$config['committee']] ?? null;
    }
}
