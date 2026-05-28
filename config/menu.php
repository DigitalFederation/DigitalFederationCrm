<?php

$menu = [

    'admin' => [
        [
            'name' => 'menu.admin.dashboard',
            'icon' => 'chart-bar',
            'route' => ['admin.dashboard'],
            'active' => ['dashboard'],
        ],
        [
            'name' => 'menu.admin.federation',
            'icon' => 'building-office',
            'route' => '',
            'can' => 'access federations',
            'active' => ['federation', 'federations'],
            'children' => [
                [
                    'name' => 'menu.admin.national_federations',
                    'route' => ['admin.federation.index'],
                    'active' => ['federations'],
                ],
                [
                    'name' => 'menu.admin.local_organizations',
                    'route' => ['admin.federation.index', ['filter[filter_is_local]' => true]],
                    'active' => ['federation'],
                ],
            ],
        ],
        [
            'name' => 'menu.admin.memberships',
            'icon' => 'document-plus',
            'route' => '',
            'can' => 'access memberships',
            'active' => ['memberships', 'membership-plans', 'membership', 'membership-plan'],
            'children' => [
                [
                    'name' => 'menu.admin.membership_packages',
                    'route' => ['admin.membership-packages.index'],
                    'active' => ['membership-packages'],
                ],
                [
                    'name' => 'menu.admin.affiliation_plans',
                    'route' => ['admin.affiliation-plans.index'],
                    'active' => ['affiliation-plans'],
                ],
                [
                    'name' => 'menu.admin.insurance_plans',
                    'route' => ['admin.insurance-plans.index'],
                    'active' => ['insurance-plans'],
                ],
                [
                    'name' => 'menu.admin.insurances',
                    'route' => ['admin.insurances.index'],
                    'active' => ['insurances'],
                ],
                [
                    'name' => 'menu.admin.member_subscriptions',
                    'route' => ['admin.member-subscriptions.index'],
                    'active' => ['member-subscriptions'],
                ],
                [
                    'name' => 'menu.admin.affiliations',
                    'route' => ['admin.affiliations.index'],
                    'active' => ['affiliations'],
                ],

            ],
        ],
        [
            'name' => 'menu.admin.entities',
            'icon' => 'building-office-2',
            'route' => ['admin.entity.index'],
            'can' => 'access entities',
            'active' => ['entity', 'entities'],
        ],
        [
            'name' => 'menu.admin.individuals',
            'icon' => 'users',
            'route' => ['admin.individual.index'],
            'can' => 'access individuals',
            'active' => ['individual', 'individuals'],
        ],
        [
            'name' => 'menu.admin.events',
            'icon' => 'ticket',
            'route' => '',
            'can' => 'access events',
            'active' => ['evt-events', 'events'],
            'children' => [
                [
                    'name' => 'menu.admin.events_list',
                    'route' => ['admin.evt-events.events.index'],
                    'active' => ['evt-events', 'events'],
                ],
                [
                    'name' => 'menu.admin.event_attributes',
                    'route' => ['admin.evt-events.attributes.index'],
                    'active' => ['attributes'],
                ],
            ],
        ],
        [
            'name' => 'menu.admin.certifications',
            'icon' => 'credit-card',
            'route' => '',
            'can' => 'access certifications',
            'active' => [
                'certification',
                'certifications',
                'certification-attributed',
            ],
            'children' => [
                [
                    'name' => 'menu.admin.diving_scientific_manager',
                    'route' => ['admin.certification.index', ['filter[committee]' => 'diving,scientific']],
                    'can' => 'access diving and scientific certifications manager',
                    'active' => ['certifications', 'certification'],
                ],
                [
                    'name' => 'menu.admin.sport_manager',
                    'route' => ['admin.certification.index', ['filter[committee]' => 'sport']],
                    'can' => 'access sport certifications manager',
                    'active' => ['certifications', 'certification'],
                ],
                [
                    'name' => 'menu.admin.diving_certifications',
                    'route' => ['admin.certification-attributed.index', ['filter[committee]' => 'diving']],
                    'can' => 'access diving certifications attributed',
                    'active' => ['certification-attributed'],
                ],
                [
                    'name' => 'menu.admin.scientific_certifications',
                    'route' => ['admin.certification-attributed.index', ['filter[committee]' => 'scientific']],
                    'can' => 'access scientific certifications attributed',
                    'active' => ['certification-attributed'],
                ],
                [
                    'name' => 'menu.admin.sport_certifications',
                    'route' => ['admin.certification-attributed.index', ['filter[committee]' => 'sport']],
                    'can' => 'access sport certifications attributed',
                    'active' => ['certification-attributed'],
                ],
            ],
        ],
        [
            'name' => 'menu.admin.licenses',
            'icon' => 'document-text',
            'route' => '',
            'can' => 'access licenses',
            'active' => [
                'license',
                'licenses',
                'license-attributed',
                'licenses-attributed',
            ],
            'children' => [
                [
                    'name' => 'menu.admin.license_manager',
                    'can' => 'access licenses manager',
                    'route' => ['admin.license.index'],
                    'active' => ['licenses', 'license'],
                ],
                [
                    'name' => 'menu.admin.diving_entities',
                    'route' => ['admin.license-attributed.index', ['filter[committee]' => 'diving', 'filter[filter_holder_type]' => 'entity']],
                    'can' => 'access diving entity licenses attributed',
                    'active' => ['licenses-attributed', 'license-attributed'],
                ],
                [
                    'name' => 'menu.admin.scientific_entities',
                    'route' => ['admin.license-attributed.index', ['filter[committee]' => 'scientific', 'filter[filter_holder_type]' => 'entity']],
                    'can' => 'access scientific entity licenses attributed',
                    'active' => ['licenses-attributed', 'license-attributed'],
                ],
                [
                    'name' => 'menu.admin.sport_entities',
                    'route' => ['admin.license-attributed.index', ['filter[committee]' => 'sport', 'filter[filter_holder_type]' => 'entity']],
                    'can' => 'access sport entity licenses attributed',
                    'active' => ['licenses-attributed', 'license-attributed'],
                ],
                [
                    'name' => 'menu.admin.diving_individuals',
                    'route' => ['admin.license-attributed.index', ['filter[committee]' => 'diving', 'filter[filter_holder_type]' => 'individual']],
                    'can' => 'access diving individual licenses attributed',
                    'active' => ['licenses-attributed', 'license-attributed'],
                ],
                [
                    'name' => 'menu.admin.scientific_individuals',
                    'route' => ['admin.license-attributed.index', ['filter[committee]' => 'scientific', 'filter[filter_holder_type]' => 'individual']],
                    'can' => 'access scientific individual licenses attributed',
                    'active' => ['licenses-attributed', 'license-attributed'],
                ],
                [
                    'name' => 'menu.admin.sport_individuals',
                    'route' => ['admin.license-attributed.index', ['filter[committee]' => 'sport', 'filter[filter_holder_type]' => 'individual']],
                    'can' => 'access sport individual licenses attributed',
                    'active' => ['licenses-attributed', 'license-attributed'],
                ],
            ],
        ],
        [
            'name' => 'menu.admin.diving_services',
            'icon' => 'globe-alt',
            'route' => '',
            'can' => 'access diving certifications attributed',
            'active' => ['entity-diving-license-validation', 'individual-diving-license-validation', 'diving-professional-certifications', 'diving-professionals'],
            'children' => [
                [
                    'name' => 'menu.admin.entity_diving_license_validation',
                    'route' => ['admin.entity_diving_license_validation.index'],
                    'can' => 'access licenses',
                    'active' => ['entity-diving-license-validation'],
                ],
                [
                    'name' => 'menu.admin.individual_diving_license_validation',
                    'route' => ['admin.individual_diving_license_validation.index'],
                    'can' => 'access licenses',
                    'active' => ['individual-diving-license-validation'],
                ],
                [
                    'name' => 'menu.admin.diving_professional_certifications',
                    'route' => ['admin.diving_professional_certifications.index'],
                    'can' => 'access diving certifications attributed',
                    'active' => ['diving-professional-certifications'],
                ],
                [
                    'name' => 'menu.admin.diving_professionals_list',
                    'route' => ['admin.diving_professionals.index'],
                    'can' => 'access individuals',
                    'active' => ['diving-professionals'],
                ],
            ],
        ],
        [
            'name' => 'menu.admin.users',
            'icon' => 'user-circle',
            'route' => '',
            'can' => 'access users',
            'active' => ['users', 'roles'],
            'children' => [
                [
                    'name' => 'menu.admin.latest_users',
                    'route' => ['admin.users.index'],
                    'active' => ['licenses', 'license'],
                ],
                [
                    'name' => 'menu.admin.role_management',
                    'route' => ['admin.role-management.index'],
                    'active' => ['role-management'],
                    'can' => 'access role management dashboard',
                ],
                [
                    'name' => 'menu.admin.permission_management',
                    'route' => ['admin.permission-management.index'],
                    'active' => ['permission-management'],
                    'can' => ['manage-permissions', 'view-permissions'],
                ],
                [
                    'name' => 'menu.admin.route_permissions',
                    'route' => ['admin.route-permissions.index'],
                    'active' => ['route-permissions'],
                    'can' => 'manage-route-permissions',
                ],
                [
                    'name' => 'menu.admin.role_mappings',
                    'route' => ['admin.role-mappings.index'],
                    'active' => ['role-mappings'],
                ],
            ],
        ],
        [
            'name' => 'menu.admin.payments',
            'icon' => 'document',
            'route' => '',
            'can' => 'access documents',
            'active' => ['documents'],
            'children' => [
                [
                    'name' => 'menu.admin.documents',
                    'route' => ['admin.document.index'],
                    'active' => ['documents'],
                ],
                [
                    'name' => 'menu.admin.invoices',
                    'route' => ['admin.document.invoices'],
                    'active' => ['documents'],
                ],
            ],
        ],
        [
            'name' => 'menu.admin.files_area',
            'icon' => 'document-arrow-down',
            'route' => '',
            'can' => 'access attachments menu',
            'active' => ['attachments'],
            'children' => [
                [
                    'name' => 'menu.admin.administrative',
                    'route' => ['admin.attachments.index'],
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.admin.sport',
                    'route' => ['admin.committee.attachments.index', 1],
                    'can' => 'access sport attachments',
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.admin.diving',
                    'route' => ['admin.committee.attachments.index', 3],
                    'can' => 'access diving attachments',
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.admin.scientific',
                    'route' => ['admin.committee.attachments.index', 2],
                    'can' => 'access scientific attachments',
                    'active' => ['attachments'],
                ],
            ],
        ],
        [
            'name' => 'menu.admin.legal_documents',
            'icon' => 'document',
            'route' => '',
            'can' => 'access official documents',
            'active' => ['official-documents'],
            'children' => [
                [
                    'name' => 'menu.admin.national_federations',
                    'route' => ['admin.official-documents.index', 'federation'],
                    'active' => ['official-documents'],
                ],
                [
                    'name' => 'menu.admin.individuals',
                    'route' => ['admin.official-documents.index', 'individual'],
                    'active' => ['official-documents'],
                ],
                [
                    'name' => 'menu.admin.entities',
                    'route' => ['admin.official-documents.index', 'entity'],
                    'active' => ['official-documents'],
                ],
            ],
        ],
        [
            'name' => 'menu.admin.settings',
            'icon' => 'chart-bar',
            'route' => '',
            'can' => 'access settings',
            'active' => ['shipping', 'products', 'districts', 'zones', 'member-number-settings', 'professional-roles', 'backups'],
            'children' => [
                [
                    'name' => 'menu.admin.reports',
                    'route' => ['admin.reports.index'],
                    'active' => ['reports'],
                ],
                [
                    'name' => 'menu.admin.statistics',
                    'route' => ['admin.reports.stats'],
                    'active' => ['reports'],
                ],
                [
                    'name' => 'menu.admin.shipping',
                    'route' => ['admin.shipping.methods.index'],
                    'active' => ['shipping'],
                ],
                [
                    'name' => 'menu.admin.products',
                    'route' => ['admin.products.index'],
                    'active' => ['products'],
                ],
                [
                    'name' => 'menu.admin.staff_roles',
                    'route' => ['admin.staff-roles.index'],
                    'active' => ['staff-roles'],
                ],
                [
                    'name' => 'menu.admin.member_number_settings',
                    'route' => ['admin.member-number-settings.index'],
                    'active' => ['member-number-settings'],
                ],
                [
                    'name' => 'menu.admin.professional_roles',
                    'route' => ['admin.professional-roles.index'],
                    'active' => ['professional-roles'],
                ],
                [
                    'name' => 'menu.admin.diving_courses_entities',
                    'route' => ['admin.diving-course.index'],
                    'active' => ['attributes'],
                ],
                [
                    'name' => 'menu.admin.districts',
                    'route' => ['admin.districts.index'],
                    'active' => ['districts'],
                ],
                [
                    'name' => 'menu.admin.zones',
                    'route' => ['admin.zones.index'],
                    'active' => ['zones'],
                ],
                [
                    'name' => 'menu.admin.operations_center',
                    'route' => ['admin.operations.index'],
                    'active' => ['operations'],
                ],
                [
                    'name' => 'menu.admin.database_backups',
                    'route' => ['admin.backups.index'],
                    'can' => 'access backups',
                    'active' => ['backups'],
                ],
            ],
        ],
    ],

    'federation' => [
        [
            'name' => 'menu.federation.dashboard',
            'icon' => 'chart-bar',
            'route' => ['federation.dashboard'],
            'active' => ['dashboard'],
        ],
        [
            'name' => 'menu.federation.memberships',
            'icon' => 'document-plus',
            'route' => ['federation.membership.index'],
            'can' => 'access memberships',
            'active' => ['membership'],
        ],
        [
            'name' => 'menu.federation.members',
            'icon' => 'user-group',
            'route' => '',
            'can' => ['access individuals', 'access entities'],
            'active' => ['individuals', 'individual', 'entities', 'entity'],
            'children' => [
                [
                    'name' => 'menu.federation.individuals',
                    'route' => ['federation.individual.index'],
                    'active' => ['individuals', 'individual'],
                ],
                [
                    'name' => 'menu.federation.entities',
                    'route' => ['federation.entity.index'],
                    'active' => ['entities', 'entity'],
                ],
            ],
        ],
        [
            'name' => 'menu.federation.sport',
            'committee' => 'sport',
            'icon' => 'flag',
            'route' => '',
            'can' => 'access sport menu',
            'active' => ['licenses-attributed', 'certifications-attributed', 'license-attributed', 'certification-attributed', 'official-documents'],
            'children' => [
                [
                    'name' => 'menu.federation.certifications',
                    'route' => ['federation.certification-attributed.index', ['filter[committee]' => 'sport']],
                    'active' => ['certifications-attributed', 'certification-attributed'],
                ],
                [
                    'name' => 'menu.federation.clubs',
                    'route' => ['federation.license-attributed.index', ['filter[committee]' => 'sport', 'filter[filter_holder_type]' => 'entity']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.federation.athletes',
                    'route' => ['federation.license-attributed.index', ['filter[committee]' => 'sport', 'filter[filter_holder_type]' => 'individual', 'filter[filter_professional]' => 'athlete']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.federation.coaches',
                    'route' => ['federation.license-attributed.index', ['filter[committee]' => 'sport', 'filter[filter_holder_type]' => 'individual', 'filter[filter_professional]' => 'coach']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.federation.referees_judges',
                    'route' => ['federation.license-attributed.index', ['filter[committee]' => 'sport', 'filter[filter_holder_type]' => 'individual', 'filter[filter_professional]' => 'refereejudge']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.federation.official_documents',
                    'route' => ['federation.official-documents.index', ['filter[committee]' => 'sport']],
                    'active' => ['official-documents'],
                ],
            ],
        ],
        [
            'name' => 'menu.federation.diving',
            'committee' => 'diving',
            'icon' => 'globe-alt',
            'route' => '',
            'can' => 'access diving menu',
            'active' => ['licenses-attributed', 'certifications-attributed', 'license-attributed', 'certification-attributed', 'official-documents'],
            'children' => [
                [
                    'name' => 'menu.federation.certifications',
                    'route' => ['federation.certification-attributed.index', ['filter[committee]' => 'diving']],
                    'active' => ['certifications-attributed', 'certification-attributed'],
                ],
                [
                    'name' => 'menu.federation.entities',
                    'route' => ['federation.license-attributed.index', ['filter[committee]' => 'diving', 'filter[filter_holder_type]' => 'entity']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.federation.instructor_leaders',
                    'route' => ['federation.license-attributed.index', ['filter[committee]' => 'diving', 'filter[filter_holder_type]' => 'individual', 'filter[filter_professional]' => 'instructorleader']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.federation.official_documents',
                    'route' => ['federation.official-documents.index', ['filter[committee]' => 'diving']],
                    'active' => ['official-documents'],
                ],
            ],
        ],
        [
            'name' => 'menu.federation.scientific',
            'committee' => 'scientific',
            'icon' => 'chart-pie',
            'route' => '',
            'can' => 'access scientific menu',
            'active' => ['licenses-attributed', 'certifications-attributed', 'license-attributed', 'certification-attributed', 'official-documents'],
            'children' => [
                [
                    'name' => 'menu.federation.certifications',
                    'route' => ['federation.certification-attributed.index', ['filter[committee]' => 'scientific']],
                    'active' => ['certifications-attributed', 'certification-attributed'],
                ],
                [
                    'name' => 'menu.federation.entities',
                    'route' => ['federation.license-attributed.index', ['filter[committee]' => 'scientific', 'filter[filter_holder_type]' => 'entity']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.federation.instructor_leaders',
                    'route' => ['federation.license-attributed.index', ['filter[committee]' => 'scientific', 'filter[filter_holder_type]' => 'individual', 'filter[filter_professional]' => 'instructorleader']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.federation.official_documents',
                    'route' => ['federation.official-documents.index', ['filter[committee]' => 'scientific']],
                    'active' => ['official-documents'],
                ],
            ],
        ],
        [
            'name' => 'menu.federation.logbook',
            'icon' => 'book-open',
            'route' => '',
            'can' => 'access diving log',
            'active' => ['diving-location', 'diving-log'],
            'children' => [
                [
                    'name' => 'menu.federation.diving_spots',
                    'route' => ['federation.diving-location.index'],
                    'active' => ['diving-location'],
                ],
                [
                    'name' => 'menu.federation.dives',
                    'route' => ['federation.diving-log.index'],
                    'active' => ['diving-log'],
                ],
            ],
        ],
        [
            'name' => 'menu.federation.events',
            'icon' => 'ticket',
            'route' => '',
            'can' => 'access events',
            'active' => ['evt-events'],
            'children' => [
                [
                    'name' => 'menu.federation.registration',
                    'route' => ['federation.evt-events.events.index'],
                    'active' => ['evt-events.event'],
                ],
                [
                    'name' => 'menu.federation.create_event',
                    'route' => ['federation.evt-events.events.create'],
                    'can' => 'manage-events',
                    'active' => ['evt-events.events.create'],
                ],
                [
                    'name' => 'menu.federation.export_events',
                    'route' => ['federation.evt-events.events.export'],
                    'can' => 'manage-events',
                    'active' => ['evt-events.events.export'],
                ],
            ],

        ],
        [
            'name' => 'menu.federation.files_area',
            'icon' => 'document-arrow-down',
            'route' => '',
            'active' => ['attachments'],
            'children' => [
                [
                    'name' => 'menu.federation.administrative',
                    'route' => ['federation.attachments.index'],
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.federation.diving',
                    'route' => ['federation.committee.attachments.index', 3],
                    'can' => 'access diving menu',
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.federation.scientific',
                    'route' => ['federation.committee.attachments.index', 2],
                    'can' => 'access scientific menu',
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.federation.sport',
                    'route' => ['federation.committee.attachments.index', 1],
                    'can' => 'access sport menu',
                    'active' => ['attachments'],
                ],
            ],
        ],
        [
            'name' => 'menu.federation.payments',
            'icon' => 'currency-dollar',
            'route' => ['federation.document.index'],
            'can' => 'access documents',
            'active' => ['documents'],
        ],
    ],

    'entity' => [
        [
            'name' => 'menu.entity.dashboard',
            'icon' => 'chart-bar',
            'route' => ['entity.dashboard'],
            'active' => ['dashboard'],
        ],
        [
            'name' => 'menu.entity.entity_plans',
            'icon' => 'building-office',
            'route' => '',
            'can' => 'access memberships',
            'active' => ['memberships', 'membership-plans', 'membership', 'membership-plan'],
            'children' => [
                [
                    'name' => 'menu.entity.insurance_plans',
                    'route' => ['entity.insurances.index'],
                    'active' => ['insurances'],
                ],
                [
                    'name' => 'menu.entity.affiliation_plans',
                    'route' => ['entity.subscriptions.index'],
                    'active' => ['subscriptions'],
                ],
            ],
        ],
        [
            'name' => 'menu.entity.members_plans',
            'icon' => 'building-office',
            'route' => '',
            'can' => 'access memberships',
            'active' => ['memberships', 'membership-plans', 'membership', 'membership-plan'],
            'children' => [
                [
                    'name' => 'menu.entity.insurance_plans',
                    'route' => ['entity.individual-insurances.index'],
                    'active' => ['insurances'],
                ],
                [
                    'name' => 'menu.entity.affiliation_plans',
                    'route' => ['entity.individual-memberships.index'],
                    'active' => ['subscriptions'],
                ],
            ],
        ],
        [
            'name' => 'menu.entity.members',
            'icon' => 'user-group',
            'route' => ['entity.individual.index'],
            'can' => ['access individuals', 'access entities'],
            'active' => ['individuals', 'individual'],
        ],
        [
            'name' => 'menu.entity.federation_organizations',
            'icon' => 'building-office',
            'route' => ['entity.federation.index'],
            'active' => ['federation', 'federations'],
        ],
        [
            'name' => 'menu.entity.sport',
            'committee' => 'sport',
            'icon' => 'flag',
            'route' => '',
            'can' => 'access individuals',
            'active' => ['licenses-attributed', 'license-attributed', 'coaches', 'athletes'],
            'children' => [
                [
                    'name' => 'menu.entity.club_licenses',
                    'route' => ['entity.license-attributed.index', ['filter[committee]' => 'sport', 'filter[filter_holder_type]' => 'entity']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.entity.athlete_licenses',
                    'route' => ['entity.license-attributed.index', ['filter[committee]' => 'sport', 'filter[filter_holder_type]' => 'individual', 'filter[filter_professional]' => 'athlete']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.entity.coach_licenses',
                    'route' => ['entity.license-attributed.index', ['filter[committee]' => 'sport', 'filter[filter_holder_type]' => 'individual', 'filter[filter_professional]' => 'coach']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
            ],
        ],
        [
            'name' => 'menu.entity.diving_services',
            'icon' => 'globe-alt',
            'route' => '',
            'can' => 'access individuals',
            'active' => ['diving-licenses', 'diving-instructors'],
            'children' => [
                [
                    'name' => 'menu.entity.service_provider_licenses',
                    'route' => ['entity.diving_licenses.index'],
                    'active' => ['diving-licenses'],
                ],
                [
                    'name' => 'menu.entity.diving_professionals',
                    'route' => ['entity.diving_professionals.index'],
                    'active' => ['diving-professionals'],
                ],
            ],
        ],
        [
            'name' => 'menu.entity.international_federation',
            'icon' => 'globe-alt',
            'route' => '',
            'can' => 'access international licenses',
            'active' => ['licenses-attributed', 'certifications-attributed', 'license-attributed', 'certification-attributed', 'diving-instructors', 'diving-courses', 'diving-licenses', 'diving-location', 'cmas-diving-instructors'],
            'children' => [
                [
                    'name' => 'menu.entity.entity_licenses',
                    'route' => ['entity.license-attributed.index', ['filter[committee]' => 'diving', 'filter[filter_holder_type]' => 'entity']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.entity.professional_licenses',
                    'route' => ['entity.license-attributed.index', ['filter[committee]' => 'diving', 'filter[filter_holder_type]' => 'individual']],
                    'active' => ['license-attributed', 'licenses-attributed'],
                ],
                [
                    'name' => 'menu.entity.certifications',
                    'route' => ['entity.certification-attributed.index', ['filter[committee]' => 'diving']],
                    'active' => ['certifications-attributed', 'certification-attributed'],
                ],
                [
                    'name' => 'menu.entity.cmas_diving_instructors',
                    'route' => ['entity.cmas-diving-instructor.index'],
                    'active' => ['cmas-diving-instructors'],
                ],
            ],
        ],
        [
            'name' => 'menu.entity.diving_locations',
            'icon' => 'map',
            'route' => ['entity.diving-location.index'],
            'active' => ['diving-location'],
        ],
        [
            'name' => 'menu.entity.licenses',
            'icon' => 'shopping-cart',
            'route' => '',
            'can' => 'access licenses',
            'active' => ['sport-license-purchase', 'cmas-diving-license-purchase', 'scientific-license-purchase', 'sport-member-license-purchase', 'cmas-diving-member-license-purchase', 'scientific-member-license-purchase', 'national-diving-member-license-purchase'],
            'children' => [
                [
                    'name' => 'menu.entity.purchase_entity_license',
                    'route' => 'entity.sport-license-purchase.index',
                    'active' => ['sport-license-purchase', 'cmas-diving-license-purchase', 'scientific-license-purchase'],
                ],
                [
                    'name' => 'menu.entity.purchase_member_licenses',
                    'route' => 'entity.sport-member-license-purchase.index',
                    'active' => ['sport-member-license-purchase', 'cmas-diving-member-license-purchase', 'scientific-member-license-purchase', 'national-diving-member-license-purchase'],
                ],
            ],
        ],
        [
            'name' => 'menu.entity.events',
            'icon' => 'ticket',
            'route' => '',
            'can' => 'access events',
            'active' => ['evt-events'],
            'children' => [
                [
                    'name' => 'menu.entity.registration',
                    'route' => ['entity.evt-events.events.index'],
                    'active' => ['evt-events.event'],
                ],
            ],
        ],
        [
            'name' => 'menu.entity.files_area',
            'icon' => 'document-arrow-down',
            'route' => '',
            'active' => ['attachments'],
            'children' => [
                [
                    'name' => 'menu.entity.administrative',
                    'route' => ['entity.attachments.index'],
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.entity.diving',
                    'route' => ['entity.committee.attachments.index', 3],
                    'can' => 'access files area menu',
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.entity.scientific',
                    'route' => ['entity.committee.attachments.index', 2],
                    'can' => 'access files area menu',
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.entity.sport',
                    'route' => ['entity.committee.attachments.index', 1],
                    'can' => 'access files area menu',
                    'active' => ['attachments'],
                ],
            ],
        ],
        [
            'name' => 'menu.entity.official_documents',
            'icon' => 'document',
            'route' => ['entity.official-documents.index'],
            'active' => ['official-documents'],
        ],
        [
            'name' => 'menu.entity.payments',
            'icon' => 'currency-dollar',
            'route' => ['entity.document.index'],
            'can' => 'access orders',
            'active' => ['documents'],
        ],
    ],

    'individual' => [
        [
            'name' => 'menu.individual.dashboard',
            'icon' => 'chart-bar',
            'route' => ['individual.dashboard'],
            'active' => ['dashboard'],
        ],
        [
            'name' => 'menu.individual.federation_organization',
            'icon' => 'building-office',
            'route' => ['individual.federation.index'],
            'active' => ['federation'],
        ],
        [
            'name' => 'menu.individual.entities',
            'icon' => 'home',
            'route' => ['individual.entity.index'],
            'active' => ['entity'],
        ],
        [
            'name' => 'menu.individual.affiliations',
            'icon' => 'wallet',
            'route' => ['individual.subscriptions.index'],
            'active' => ['subscriptions'],
        ],
        [
            'name' => 'menu.individual.insurances',
            'icon' => 'identification',
            'route' => ['individual.insurance.index'],
            'active' => ['insurance'],
        ],
        [
            'name' => 'menu.individual.diving_professional',
            'icon' => 'globe-alt',
            'route' => '',
            'active' => ['diving-certifications', 'official-documents', 'diving-entities'],
            'children' => [
                [
                    'name' => 'menu.individual.diving_certifications',
                    'route' => ['individual.diving_certifications.index'],
                    'active' => ['diving-certifications'],
                ],
                [
                    'name' => 'menu.individual.diving_official_documents',
                    'route' => ['individual.official-documents.index', 'diving-professional'],
                    'active' => ['official-documents'],
                ],
                [
                    'name' => 'menu.individual.diving_entities',
                    'route' => ['individual.diving_entities.index'],
                    'active' => ['diving-entities'],
                ],
                [
                    'name' => 'menu.individual.technical_director_positions',
                    'route' => ['individual.technical_director_positions.index'],
                    'active' => ['technical-director-positions'],
                ],
            ],
        ],
        [
            'name' => 'menu.individual.my_certifications',
            'icon' => 'credit-card',
            'can' => ['access diver menu', 'access scientific menu', 'access sport menu'],
            'route' => ['individual.certification-card.index'],
            'active' => ['certification-card', 'certification-attributed'],
            'children_backup' => [
                [
                    'name' => 'menu.individual.cards',
                    'route' => ['individual.certification-card.index'],
                    'active' => ['certification-card'],
                ],
                [
                    'name' => 'menu.individual.diving',
                    'route' => ['individual.certification-attributed.index', ['filter[committee]' => 'diving']],
                    'can' => 'access diver menu',
                    'active' => ['certification-attributed'],
                ],
                [
                    'name' => 'menu.individual.scientific',
                    'route' => ['individual.certification-attributed.index', ['filter[committee]' => 'scientific']],
                    'can' => 'access scientific menu',
                    'active' => ['certification-attributed'],
                ],
                [
                    'name' => 'menu.individual.sport',
                    'route' => ['individual.certification-attributed.index', ['filter[committee]' => 'sport']],
                    'can' => 'access sport menu',
                    'active' => ['certification-attributed'],
                ],
            ],
        ],
        [
            'name' => 'menu.individual.my_licenses',
            'icon' => 'document-text',
            'can' => [],
            'route' => '',
            'active' => ['licenses-attributed', 'license-attributed'],
            'children' => [
                [
                    'name' => 'menu.individual.diving',
                    'route' => ['individual.license-attributed.index', ['filter[committee]' => 'diving']],
                    'can' => ['access diver menu'],
                    'active' => ['licenses-attributed'],
                ],
                [
                    'name' => 'menu.individual.scientific',
                    'route' => ['individual.license-attributed.index', ['filter[committee]' => 'scientific']],
                    'can' => ['access scientific menu'],
                    'active' => ['licenses-attributed'],
                ],
                [
                    'name' => 'menu.individual.sport',
                    'route' => ['individual.license-attributed.index', ['filter[committee]' => 'sport']],
                    'can' => [],
                    'active' => ['licenses-attributed'],
                ],
            ],
        ],
        [
            'name' => 'menu.individual.personal_documents',
            'icon' => 'document',
            'route' => '',
            'can' => [],
            'active' => ['official-documents'],
            'children' => [
                [
                    'name' => 'menu.individual.diver',
                    'route' => ['individual.official-documents.index', 'diver'],
                    'can' => ['access diver official documents', 'access diving official documents'],
                    'active' => ['official-documents'],
                ],
                [
                    'name' => 'menu.individual.instructor_leader',
                    'route' => ['individual.official-documents.index', 'instructor-leader'],
                    'can' => ['access diver menu', 'access scientific menu'],
                    'active' => ['official-documents'],
                ],
                [
                    'name' => 'menu.individual.coach',
                    'route' => ['individual.official-documents.index', 'coach'],
                    'can' => ['access coach menu'],
                    'active' => ['official-documents'],
                ],
                [
                    'name' => 'menu.individual.referee_judge',
                    'route' => ['individual.official-documents.index', 'referee-judge'],
                    'can' => ['access referee menu', 'access judge menu'],
                    'active' => ['official-documents'],
                ],
                [
                    'name' => 'menu.individual.athlete',
                    'route' => ['individual.official-documents.index', 'athlete'],
                    'can' => [],
                    'active' => ['official-documents'],
                ],
            ],
        ],
        [
            'name' => 'menu.individual.athlete',
            'icon' => 'user',
            'route' => '',
            'can' => [],
            'active' => ['athlete', 'official-documents'],
            'children' => [
                [
                    'name' => 'menu.individual.clubs',
                    'route' => ['individual.athlete.index', ['filter[status]' => 'active']],
                    'active' => ['athlete'],
                ],
                [
                    'name' => 'menu.individual.club_requests',
                    'route' => ['individual.athlete.index'],
                    'active' => ['athlete'],
                ],
            ],
        ], // Athlete - No permission required so individuals can see pending invitations
        [
            'name' => 'menu.individual.coach',
            'icon' => 'identification',
            'route' => '',
            'can' => ['access coach menu'],
            'active' => ['coach', 'certification-attributed', 'official-documents'],
            'children' => [
                [
                    'name' => 'menu.individual.clubs',
                    'route' => ['individual.coach.index', ['filter[status]' => 'active']],
                    'active' => ['athlete'],
                ],
                [
                    'name' => 'menu.individual.clubs_requests',
                    'route' => ['individual.coach.index'],
                    'active' => ['coach'],
                ],
            ],
        ], // Coach
        [
            'name' => 'menu.individual.instructor_leader',
            'icon' => 'academic-cap',
            'route' => '',
            'can' => ['access instructor menu'],
            'active' => ['certification-validate', 'diving-log-validation', 'instructor', 'official-documents'],
            'children' => [
                [
                    'name' => 'menu.individual.certifications_to_approve',
                    'route' => ['individual.certification-validate.index'],
                    'active' => ['certification-validate'],
                ],
                [
                    'name' => 'menu.individual.issued_certifications',
                    'route' => ['individual.certification-validate.index', ['filter[filter_status]' => 'active']],
                    'active' => ['certification-validate'],
                ],
                [
                    'name' => 'menu.individual.dives_to_approve',
                    'route' => ['individual.diving-log-validation.index'],
                    'active' => ['diving-log-validation'],
                ],
                [
                    'name' => 'menu.individual.diving_entities',
                    'route' => ['individual.instructor.index', 'diving'],
                    'active' => ['instructor'],
                ],
                [
                    'name' => 'menu.individual.scientific_entities',
                    'route' => ['individual.instructor.index', 'scientific'],
                    'active' => ['instructor'],
                ],
            ],
        ], // Instructor
        [
            'name' => 'menu.individual.referee_judge',
            'icon' => 'briefcase',
            'route' => '',
            'can' => ['access referee menu'],
            'active' => ['official-documents'],
            'children' => [],
        ],
        [
            'name' => 'menu.individual.events',
            'icon' => 'ticket',
            'route' => ['individual.evt-events.events.index'],
            'can' => [],
            'active' => ['evt-events'],
        ],
        [
            'name' => 'menu.individual.files_area',
            'icon' => 'document-arrow-down',
            'route' => '',
            'can' => [],
            'active' => ['attachments'],
            'children' => [
                [
                    'name' => 'menu.individual.administrative',
                    'route' => ['individual.attachments.index'],
                    'can' => [],
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.individual.diving',
                    'route' => ['individual.committee.attachments.index', 3],
                    'can' => 'access diver menu',
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.individual.scientific',
                    'route' => ['individual.committee.attachments.index', 2],
                    'can' => 'access scientific menu',
                    'active' => ['attachments'],
                ],
                [
                    'name' => 'menu.individual.sport',
                    'route' => ['individual.committee.attachments.index', 1],
                    'can' => 'access sport menu',
                    'active' => ['attachments'],
                ],
            ],
        ],
        [
            'name' => 'menu.individual.payments',
            'icon' => 'currency-dollar',
            'route' => ['individual.document.index'],
            'can' => [],
            'active' => ['documents'],
        ],

    ],
];

return $menu;
