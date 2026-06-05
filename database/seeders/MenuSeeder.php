<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds the DB-driven navigation menus (sidebar) for each user group.
 *
 * Without a menu seed a fresh install renders no sidebar at all. This data was
 * derived from a real deployment's menu, route-validated against the app's
 * registered routes, and pruned of any links to routes that do not exist here.
 * Item names are plain strings; MenuItem::getDisplayText() runs them through
 * __() so they may also be translation keys.
 */
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $items =
         [
             0 => [
                 'id' => 1,
                 'parent' => null,
                 'group' => 'admin',
                 'name' => 'Painel de Controlo',
                 'icon' => 'img/svg/speedometer.svg',
                 'order' => 0,
                 'route' => 'admin.dashboard',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             1 => [
                 'id' => 2,
                 'parent' => null,
                 'group' => 'admin',
                 'name' => 'Organizações',
                 'icon' => 'img/svg/buildings.svg',
                 'order' => 0,
                 'route' => 'admin.federation.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             2 => [
                 'id' => 13,
                 'parent' => 12,
                 'group' => 'admin',
                 'name' => 'Gerir Certificações',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'admin.certification.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             3 => [
                 'id' => 14,
                 'parent' => 12,
                 'group' => 'admin',
                 'name' => 'Certificações Mergulho',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'admin.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             4 => [
                 'id' => 3,
                 'parent' => null,
                 'group' => 'admin',
                 'name' => 'Membros',
                 'icon' => 'img/svg/people-fill.svg',
                 'order' => 3,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             5 => [
                 'id' => 15,
                 'parent' => 12,
                 'group' => 'admin',
                 'name' => 'Certificações Mergulho Cientifico',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'admin.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             6 => [
                 'id' => 6,
                 'parent' => null,
                 'group' => 'admin',
                 'name' => 'Desporto',
                 'icon' => 'img/svg/circle-fill.svg',
                 'order' => 4,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             7 => [
                 'id' => 16,
                 'parent' => 12,
                 'group' => 'admin',
                 'name' => 'Gerir Licenças',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'admin.license.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             8 => [
                 'id' => 17,
                 'parent' => 12,
                 'group' => 'admin',
                 'name' => 'Entidades Recreativas',
                 'icon' => null,
                 'order' => 5,
                 'route' => 'admin.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             9 => [
                 'id' => 18,
                 'parent' => 12,
                 'group' => 'admin',
                 'name' => 'Entidades Cientificas',
                 'icon' => null,
                 'order' => 6,
                 'route' => 'admin.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             10 => [
                 'id' => 25,
                 'parent' => null,
                 'group' => 'admin',
                 'name' => 'Sistema',
                 'icon' => 'img/svg/boxes.svg',
                 'order' => 7,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             11 => [
                 'id' => 19,
                 'parent' => 12,
                 'group' => 'admin',
                 'name' => 'Profissionais Mergulho Recreativo',
                 'icon' => null,
                 'order' => 7,
                 'route' => 'admin.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             12 => [
                 'id' => 20,
                 'parent' => 12,
                 'group' => 'admin',
                 'name' => 'Profissionais Mergulho Cientifico',
                 'icon' => null,
                 'order' => 8,
                 'route' => 'admin.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             13 => [
                 'id' => 66,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Painel de Controlo',
                 'icon' => 'img/svg/speedometer.svg',
                 'order' => 1,
                 'route' => 'entity.dashboard',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             14 => [
                 'id' => 68,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Membros',
                 'icon' => 'img/svg/people-fill.svg',
                 'order' => 3,
                 'route' => 'entity.individual.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             15 => [
                 'id' => 69,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Planos para Entidade',
                 'icon' => 'img/svg/person-rolodex.svg',
                 'order' => 4,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             16 => [
                 'id' => 73,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Planos para Membros',
                 'icon' => 'img/svg/person-rolodex.svg',
                 'order' => 4,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             17 => [
                 'id' => 80,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Desporto',
                 'icon' => 'img/svg/circle-fill.svg',
                 'order' => 6,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             18 => [
                 'id' => 85,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Eventos',
                 'icon' => 'img/svg/ticket-detailed.svg',
                 'order' => 7,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             19 => [
                 'id' => 89,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Área de Ficheiros',
                 'icon' => 'img/svg/file-earmark-arrow-down.svg',
                 'order' => 8,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             20 => [
                 'id' => 91,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Pagamentos',
                 'icon' => 'img/svg/money.svg',
                 'order' => 9,
                 'route' => 'entity.document.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             21 => [
                 'id' => 93,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Mergulho Recreativo',
                 'icon' => 'img/svg/icon-mask-diver-white.svg',
                 'order' => 11,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             22 => [
                 'id' => 98,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Mergulho Científico',
                 'icon' => 'img/svg/compass.svg',
                 'order' => 12,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             23 => [
                 'id' => 103,
                 'parent' => null,
                 'group' => 'entity',
                 'name' => 'Logbook',
                 'icon' => 'img/svg/book.svg',
                 'order' => 13,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             24 => [
                 'id' => 29,
                 'parent' => null,
                 'group' => 'federation',
                 'name' => 'Painel de Controlo',
                 'icon' => 'img/svg/speedometer.svg',
                 'order' => 1,
                 'route' => 'federation.dashboard',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             25 => [
                 'id' => 36,
                 'parent' => null,
                 'group' => 'federation',
                 'name' => 'Membros',
                 'icon' => 'img/svg/people-fill.svg',
                 'order' => 1,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             26 => [
                 'id' => 30,
                 'parent' => null,
                 'group' => 'federation',
                 'name' => 'Planos e Subscrições',
                 'icon' => 'img/svg/person-rolodex.svg',
                 'order' => 2,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             27 => [
                 'id' => 39,
                 'parent' => null,
                 'group' => 'federation',
                 'name' => 'Desporto',
                 'icon' => 'img/svg/circle-fill.svg',
                 'order' => 4,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             28 => [
                 'id' => 46,
                 'parent' => null,
                 'group' => 'federation',
                 'name' => 'Mergulho Recreativo',
                 'icon' => 'img/svg/icon-mask-diver-white.svg',
                 'order' => 5,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             29 => [
                 'id' => 51,
                 'parent' => null,
                 'group' => 'federation',
                 'name' => 'Mergulho Científico',
                 'icon' => 'img/svg/compass.svg',
                 'order' => 6,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             30 => [
                 'id' => 56,
                 'parent' => null,
                 'group' => 'federation',
                 'name' => 'Eventos',
                 'icon' => 'img/svg/ticket-detailed.svg',
                 'order' => 7,
                 'route' => 'federation.evt-events.events.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             31 => [
                 'id' => 57,
                 'parent' => null,
                 'group' => 'federation',
                 'name' => 'Área de Ficheiros',
                 'icon' => 'img/svg/file-earmark-arrow-down.svg',
                 'order' => 8,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             32 => [
                 'id' => 61,
                 'parent' => null,
                 'group' => 'federation',
                 'name' => 'Pagamentos',
                 'icon' => 'img/svg/money.svg',
                 'order' => 9,
                 'route' => 'federation.document.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             33 => [
                 'id' => 106,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Painel de Controlo',
                 'icon' => 'img/svg/speedometer.svg',
                 'order' => 1,
                 'route' => 'individual.dashboard',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             34 => [
                 'id' => 107,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Organizações',
                 'icon' => 'img/svg/compass.svg',
                 'order' => 2,
                 'route' => 'individual.federation.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             35 => [
                 'id' => 108,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Entidades',
                 'icon' => 'img/svg/house-door.svg',
                 'order' => 3,
                 'route' => 'individual.entity.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             36 => [
                 'id' => 110,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Seguros',
                 'icon' => 'img/svg/boxes.svg',
                 'order' => 5,
                 'route' => 'individual.insurance.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             37 => [
                 'id' => 111,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Treinador',
                 'icon' => 'img/svg/person-rolodex.svg',
                 'order' => 6,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             38 => [
                 'id' => 117,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Árbitro & Juiz',
                 'icon' => 'img/svg/person-workspace.svg',
                 'order' => 7,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             39 => [
                 'id' => 122,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Atleta',
                 'icon' => 'img/svg/icon-athlete-white.svg',
                 'order' => 8,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             40 => [
                 'id' => 127,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Eventos',
                 'icon' => 'img/svg/ticket-detailed.svg',
                 'order' => 9,
                 'route' => 'individual.evt-events.events.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             41 => [
                 'id' => 128,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Área de Ficheiros',
                 'icon' => 'img/svg/file-earmark-arrow-down.svg',
                 'order' => 10,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             42 => [
                 'id' => 130,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Pagamentos',
                 'icon' => 'img/svg/money.svg',
                 'order' => 11,
                 'route' => 'individual.document.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             43 => [
                 'id' => 132,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Mergulhador',
                 'icon' => 'img/svg/icon-mask-diver-white.svg',
                 'order' => 13,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             44 => [
                 'id' => 135,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Instrutor & Líder',
                 'icon' => 'img/svg/icon-student-cap-white.svg',
                 'order' => 14,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             45 => [
                 'id' => 141,
                 'parent' => null,
                 'group' => 'individual',
                 'name' => 'Logbook',
                 'icon' => 'img/svg/book.svg',
                 'order' => 15,
                 'route' => null,
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             46 => [
                 'id' => 4,
                 'parent' => 3,
                 'group' => 'admin',
                 'name' => 'Entidades',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'admin.entity.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             47 => [
                 'id' => 7,
                 'parent' => 6,
                 'group' => 'admin',
                 'name' => 'Gerir Certificações',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'admin.certification.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             48 => [
                 'id' => 26,
                 'parent' => 25,
                 'group' => 'admin',
                 'name' => 'Utilizadores',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'admin.users.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             49 => [
                 'id' => 5,
                 'parent' => 3,
                 'group' => 'admin',
                 'name' => 'Individuais',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'admin.individual.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             50 => [
                 'id' => 8,
                 'parent' => 6,
                 'group' => 'admin',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'admin.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             51 => [
                 'id' => 27,
                 'parent' => 25,
                 'group' => 'admin',
                 'name' => 'Funções',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'admin.role-management.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             52 => [
                 'id' => 9,
                 'parent' => 6,
                 'group' => 'admin',
                 'name' => 'Gerir Licenças',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'admin.license.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             53 => [
                 'id' => 28,
                 'parent' => 25,
                 'group' => 'admin',
                 'name' => 'Menu Items',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'admin.menu-management.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             54 => [
                 'id' => 10,
                 'parent' => 6,
                 'group' => 'admin',
                 'name' => 'Clubes',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'admin.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             55 => [
                 'id' => 11,
                 'parent' => 6,
                 'group' => 'admin',
                 'name' => 'Licenças Individuais',
                 'icon' => null,
                 'order' => 5,
                 'route' => 'admin.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             56 => [
                 'id' => 70,
                 'parent' => 69,
                 'group' => 'entity',
                 'name' => 'Planos de Filiação',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'entity.subscriptions.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             57 => [
                 'id' => 75,
                 'parent' => 73,
                 'group' => 'entity',
                 'name' => 'Planos de Filiação',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'entity.individual-memberships.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             58 => [
                 'id' => 81,
                 'parent' => 80,
                 'group' => 'entity',
                 'name' => 'Licenças Entidade',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'entity.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             59 => [
                 'id' => 86,
                 'parent' => 85,
                 'group' => 'entity',
                 'name' => 'Inscrições',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'entity.evt-events.events.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             60 => [
                 'id' => 90,
                 'parent' => 89,
                 'group' => 'entity',
                 'name' => 'Administrativos',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'entity.attachments.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             61 => [
                 'id' => 94,
                 'parent' => 93,
                 'group' => 'entity',
                 'name' => 'Licenças',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'entity.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             62 => [
                 'id' => 99,
                 'parent' => 98,
                 'group' => 'entity',
                 'name' => 'Licenças',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'entity.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             63 => [
                 'id' => 104,
                 'parent' => 103,
                 'group' => 'entity',
                 'name' => 'Locais de Mergulho',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'entity.diving-location.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             64 => [
                 'id' => 72,
                 'parent' => 69,
                 'group' => 'entity',
                 'name' => 'Planos de Seguros',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'entity.insurances.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             65 => [
                 'id' => 76,
                 'parent' => 73,
                 'group' => 'entity',
                 'name' => 'Planos de Seguros',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'entity.individual-insurances.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             66 => [
                 'id' => 82,
                 'parent' => 80,
                 'group' => 'entity',
                 'name' => 'Treinadores',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'entity.coach.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             67 => [
                 'id' => 95,
                 'parent' => 93,
                 'group' => 'entity',
                 'name' => 'Instrutores & Líderes',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'entity.diving-instructor.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             68 => [
                 'id' => 100,
                 'parent' => 98,
                 'group' => 'entity',
                 'name' => 'Instrutores & Líderes',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'entity.scientific-instructor.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             69 => [
                 'id' => 105,
                 'parent' => 103,
                 'group' => 'entity',
                 'name' => 'Mergulhos para Aprovar',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'entity.diving-log-validation.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             70 => [
                 'id' => 83,
                 'parent' => 80,
                 'group' => 'entity',
                 'name' => 'Atletas',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'entity.athlete.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             71 => [
                 'id' => 96,
                 'parent' => 93,
                 'group' => 'entity',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'entity.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             72 => [
                 'id' => 101,
                 'parent' => 98,
                 'group' => 'entity',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'entity.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             73 => [
                 'id' => 84,
                 'parent' => 80,
                 'group' => 'entity',
                 'name' => 'Área Ficheiros',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'entity.committee.attachments.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             74 => [
                 'id' => 97,
                 'parent' => 93,
                 'group' => 'entity',
                 'name' => 'Área Ficheiros',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'entity.committee.attachments.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             75 => [
                 'id' => 102,
                 'parent' => 98,
                 'group' => 'entity',
                 'name' => 'Área Ficheiros',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'entity.committee.attachments.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             76 => [
                 'id' => 31,
                 'parent' => 30,
                 'group' => 'federation',
                 'name' => 'Pacotes de Planos',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'federation.local-membership-plan.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             77 => [
                 'id' => 37,
                 'parent' => 36,
                 'group' => 'federation',
                 'name' => 'Individuais',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'federation.individual.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             78 => [
                 'id' => 40,
                 'parent' => 39,
                 'group' => 'federation',
                 'name' => 'Clubes',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'federation.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             79 => [
                 'id' => 47,
                 'parent' => 46,
                 'group' => 'federation',
                 'name' => 'Entidades Mergulho',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'federation.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             80 => [
                 'id' => 52,
                 'parent' => 51,
                 'group' => 'federation',
                 'name' => 'Entidades Científicas',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'federation.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             81 => [
                 'id' => 58,
                 'parent' => 57,
                 'group' => 'federation',
                 'name' => 'Administrativos',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'federation.attachments.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             82 => [
                 'id' => 32,
                 'parent' => 30,
                 'group' => 'federation',
                 'name' => 'Planos de Filiações',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'federation.entity-affiliations.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             83 => [
                 'id' => 38,
                 'parent' => 36,
                 'group' => 'federation',
                 'name' => 'Entidades',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'federation.entity.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             84 => [
                 'id' => 41,
                 'parent' => 39,
                 'group' => 'federation',
                 'name' => 'Treinadores',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'federation.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             85 => [
                 'id' => 48,
                 'parent' => 46,
                 'group' => 'federation',
                 'name' => 'Profissionais de Mergulho',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'federation.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             86 => [
                 'id' => 53,
                 'parent' => 51,
                 'group' => 'federation',
                 'name' => 'Profissionais de Mergulho',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'federation.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             87 => [
                 'id' => 59,
                 'parent' => 57,
                 'group' => 'federation',
                 'name' => 'Mergulho Recreativo',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'federation.committee.attachments.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             88 => [
                 'id' => 33,
                 'parent' => 30,
                 'group' => 'federation',
                 'name' => 'Planos de Seguros',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'federation.entity-insurances.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             89 => [
                 'id' => 42,
                 'parent' => 39,
                 'group' => 'federation',
                 'name' => 'Atletas',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'federation.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             90 => [
                 'id' => 49,
                 'parent' => 46,
                 'group' => 'federation',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'federation.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             91 => [
                 'id' => 54,
                 'parent' => 51,
                 'group' => 'federation',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'federation.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             92 => [
                 'id' => 60,
                 'parent' => 57,
                 'group' => 'federation',
                 'name' => 'Mergulho Científico',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'federation.committee.attachments.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             93 => [
                 'id' => 34,
                 'parent' => 30,
                 'group' => 'federation',
                 'name' => 'Seguros Subscritos',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'federation.individual-insurances.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             94 => [
                 'id' => 43,
                 'parent' => 39,
                 'group' => 'federation',
                 'name' => 'Árbitros e Juízes',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'federation.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             95 => [
                 'id' => 50,
                 'parent' => 46,
                 'group' => 'federation',
                 'name' => 'Documentos Oficiais',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'federation.official-documents.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             96 => [
                 'id' => 55,
                 'parent' => 51,
                 'group' => 'federation',
                 'name' => 'Documentos Oficiais',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'federation.official-documents.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             97 => [
                 'id' => 44,
                 'parent' => 39,
                 'group' => 'federation',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 5,
                 'route' => 'federation.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             98 => [
                 'id' => 45,
                 'parent' => 39,
                 'group' => 'federation',
                 'name' => 'Documentos Oficiais',
                 'icon' => null,
                 'order' => 6,
                 'route' => 'federation.official-documents.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             99 => [
                 'id' => 112,
                 'parent' => 111,
                 'group' => 'individual',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'individual.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             100 => [
                 'id' => 118,
                 'parent' => 117,
                 'group' => 'individual',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'individual.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             101 => [
                 'id' => 123,
                 'parent' => 122,
                 'group' => 'individual',
                 'name' => 'Licenças',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'individual.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             102 => [
                 'id' => 129,
                 'parent' => 128,
                 'group' => 'individual',
                 'name' => 'Administrativos',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'individual.attachments.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             103 => [
                 'id' => 133,
                 'parent' => 132,
                 'group' => 'individual',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'individual.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             104 => [
                 'id' => 136,
                 'parent' => 135,
                 'group' => 'individual',
                 'name' => 'Certificações',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'individual.certification-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             105 => [
                 'id' => 142,
                 'parent' => 141,
                 'group' => 'individual',
                 'name' => 'Mergulhos',
                 'icon' => null,
                 'order' => 1,
                 'route' => 'individual.diving-log.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             106 => [
                 'id' => 113,
                 'parent' => 111,
                 'group' => 'individual',
                 'name' => 'Licenças',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'individual.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             107 => [
                 'id' => 119,
                 'parent' => 117,
                 'group' => 'individual',
                 'name' => 'Licenças',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'individual.license-attributed.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             108 => [
                 'id' => 124,
                 'parent' => 122,
                 'group' => 'individual',
                 'name' => 'Documentos Atleta',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'individual.official-documents.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             109 => [
                 'id' => 134,
                 'parent' => 132,
                 'group' => 'individual',
                 'name' => 'Documentos Mergulhador',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'individual.official-documents.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             110 => [
                 'id' => 137,
                 'parent' => 135,
                 'group' => 'individual',
                 'name' => 'Certificações para Aprovar',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'individual.certification-validate.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             111 => [
                 'id' => 143,
                 'parent' => 141,
                 'group' => 'individual',
                 'name' => 'Buddies',
                 'icon' => null,
                 'order' => 2,
                 'route' => 'individual.diving-buddy.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             112 => [
                 'id' => 114,
                 'parent' => 111,
                 'group' => 'individual',
                 'name' => 'Documentos Treinador',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'individual.official-documents.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             113 => [
                 'id' => 120,
                 'parent' => 117,
                 'group' => 'individual',
                 'name' => 'Documentos Arbitragem',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'individual.official-documents.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             114 => [
                 'id' => 125,
                 'parent' => 122,
                 'group' => 'individual',
                 'name' => 'Clubes',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'individual.athlete.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             115 => [
                 'id' => 138,
                 'parent' => 135,
                 'group' => 'individual',
                 'name' => 'Histórico de Certificações',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'individual.certification-validate.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             116 => [
                 'id' => 144,
                 'parent' => 141,
                 'group' => 'individual',
                 'name' => 'Locais de Mergulho',
                 'icon' => null,
                 'order' => 3,
                 'route' => 'individual.diving-location.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             117 => [
                 'id' => 115,
                 'parent' => 111,
                 'group' => 'individual',
                 'name' => 'Clubes',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'individual.coach.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             118 => [
                 'id' => 139,
                 'parent' => 135,
                 'group' => 'individual',
                 'name' => 'Mergulhos para Aprovar',
                 'icon' => null,
                 'order' => 4,
                 'route' => 'individual.diving-log-validation.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
             119 => [
                 'id' => 140,
                 'parent' => 135,
                 'group' => 'individual',
                 'name' => 'Documentos Instrutor & Líder',
                 'icon' => null,
                 'order' => 5,
                 'route' => 'individual.official-documents.index',
                 'params' => [
                 ],
                 'permissions' => [
                 ],
             ],
         ];

        $menus = [];
        foreach (['admin', 'federation', 'entity', 'individual'] as $group) {
            $menus[$group] = Menu::updateOrCreate(
                ['machine_name' => $group],
                ['name' => Str::headline($group).' Menu', 'description' => Str::headline($group).' navigation menu', 'active' => true]
            );
            MenuItem::where('menu_id', $menus[$group]->id)->delete();
        }

        $idMap = [];
        foreach ($items as $it) {
            $parentId = $it['parent'] !== null ? ($idMap[$it['parent']] ?? null) : null;
            $menuItem = MenuItem::create([
                'menu_id' => $menus[$it['group']]->id,
                'parent_id' => $parentId,
                'name' => $it['name'],
                'icon' => $it['icon'],
                'order' => $it['order'],
                'route_name' => $it['route'],
                'route_parameters' => $it['params'],
                'permissions' => $it['permissions'],
                'visible' => true,
            ]);
            $idMap[$it['id']] = $menuItem->id;
        }

        $this->command?->info('Seeded '.count($items).' menu items across '.count($menus).' menus.');
    }
}
