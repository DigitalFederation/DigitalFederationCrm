<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommitteeCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\GeoZone;
use App\Models\Sport;
use Domain\Entities\Models\Entity;
use Domain\Federations\Models\Federation;
use Domain\Individuals\Models\ProfessionalRole;
use Domain\Licenses\Models\License;
use Domain\Licenses\Models\LicenseAttributed;
use Domain\Licenses\Scopes\ExcludeInternationalScope;
use Illuminate\Contracts\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SeparatedLicenseAttributedController extends Controller
{
    // ===================
    // SPORT (National)
    // ===================

    /**
     * Sport entity licenses (Clubes Desportivos)
     * Committee: SPORT, International: false, Holder: entity
     */
    public function sportEntity(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::Sport->value,
            false,
            'entity',
            __('licenses.admin_sport_entity_licenses_title'),
            __('licenses.admin_sport_entity_licenses_subtitle')
        );
    }

    /**
     * Sport individual licenses (Atletas e Treinadores)
     * Committee: SPORT, International: false, Holder: individual
     */
    public function sportIndividual(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::Sport->value,
            false,
            'individual',
            __('licenses.admin_sport_individual_licenses_title'),
            __('licenses.admin_sport_individual_licenses_subtitle')
        );
    }

    // ===================
    // National diving services
    // ===================

    /**
     * National diving service entity licenses.
     * Committee: DIVINGSERVICES, International: false, Holder: entity
     */
    public function nationalDivingEntity(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::DivingServices->value,
            false,
            'entity',
            __('licenses.admin_national_diving_entity_licenses_title'),
            __('licenses.admin_national_diving_entity_licenses_subtitle')
        );
    }

    /**
     * National diving service individual licenses.
     * Committee: DIVINGSERVICES, International: false, Holder: individual
     */
    public function nationalDivingIndividual(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::DivingServices->value,
            false,
            'individual',
            __('licenses.admin_national_diving_individual_licenses_title'),
            __('licenses.admin_national_diving_individual_licenses_subtitle')
        );
    }

    // ===================
    // CMAS DIVING (International)
    // ===================

    /**
     * International diving entity licenses.
     * Committee: DIVING, International: true, Holder: entity
     */
    public function divingCmasEntity(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::Diving->value,
            true,
            'entity',
            __('licenses.admin_cmas_diving_entity_licenses_title'),
            __('licenses.admin_cmas_diving_entity_licenses_subtitle')
        );
    }

    /**
     * International diving individual licenses.
     * Committee: DIVING, International: true, Holder: individual
     */
    public function divingCmasIndividual(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::Diving->value,
            true,
            'individual',
            __('licenses.admin_cmas_diving_individual_licenses_title'),
            __('licenses.admin_cmas_diving_individual_licenses_subtitle')
        );
    }

    // ===================
    // SCIENTIFIC (International)
    // ===================

    /**
     * Scientific entity licenses (Centros de Mergulho Cientifico)
     * Committee: SCIENTIFIC, International: true, Holder: entity
     */
    public function scientificEntity(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::Scientific->value,
            true,
            'entity',
            __('licenses.admin_scientific_entity_licenses_title'),
            __('licenses.admin_scientific_entity_licenses_subtitle')
        );
    }

    /**
     * Scientific individual licenses (Profissionais de Mergulho Cientifico)
     * Committee: SCIENTIFIC, International: true, Holder: individual
     */
    public function scientificIndividual(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::Scientific->value,
            true,
            'individual',
            __('licenses.admin_scientific_individual_licenses_title'),
            __('licenses.admin_scientific_individual_licenses_subtitle')
        );
    }

    /**
     * Render the licenses attributed page with fixed committee, international, and holder type parameters
     */
    private function renderLicensesPage(
        string $committee,
        bool $isInternational,
        string $holderType,
        string $pageTitle,
        string $pageSubtitle
    ): View {
        $query = QueryBuilder::for(LicenseAttributed::class)
            ->with(['owner', 'license', 'license.committee', 'license.sport', 'license.professionalRole', 'federation.country'])
            ->allowedFilters([
                AllowedFilter::scope('filter_expiration_end', 'expiration_after'),
                AllowedFilter::scope('filter_expiration_start', 'expiration_before'),
                AllowedFilter::scope('filter_emission_end', 'emissionAfter'),
                AllowedFilter::scope('filter_emission_start', 'emissionBefore'),
                AllowedFilter::scope('filter_federation', 'federation'),
                AllowedFilter::scope('filter_entity', 'entity'),
                AllowedFilter::scope('filter_country', 'country'),
                AllowedFilter::scope('filter_cmas_code', 'cmas_code'),
                AllowedFilter::scope('filter_sport', 'sport'),
                AllowedFilter::scope('filter_category', 'professionalRole'),
                AllowedFilter::scope('filter_name', 'license_name'),
                AllowedFilter::scope('filter_status', 'license_attributed_status'),
                AllowedFilter::scope('filter_zone'),
                AllowedFilter::scope('filter_professional'),
                AllowedFilter::scope('filter_license', 'licenseId'),
                AllowedFilter::scope('filter_first_name', 'individualFirstName'),
                AllowedFilter::scope('filter_surname', 'individualSurname'),
                AllowedFilter::scope('filter_member_number', 'individualMemberNumber'),
                AllowedFilter::scope('filter_payment_status', 'filterPaymentStatus'),
                AllowedFilter::scope('filter_entity_name', 'entityName'),
            ]);

        // Apply holder type filter (entity or individual)
        $query->where('model_type', $holderType);

        // Apply committee filter
        $query->whereHas('license', function ($q) use ($committee) {
            $q->whereHas('committee', function ($cq) use ($committee) {
                $cq->where('code', $committee);
            });
        });

        // Apply international filter
        if ($isInternational) {
            $query->withoutGlobalScope(ExcludeInternationalScope::class)
                ->whereHas('license', function ($q) {
                    $q->withoutGlobalScope(ExcludeInternationalScope::class)
                        ->whereHas('committee', fn ($cq) => $cq->where('is_international', true));
                });
        } else {
            $query->whereHas('license', function ($q) {
                $q->whereHas('committee', fn ($cq) => $cq->where('is_international', false));
            });
        }

        $licenses = $query
            ->withPaymentStatus()
            ->latest()
            ->paginate()
            ->appends(request()->query());

        $federations = Federation::select('id', 'name')->orderBy('name')->get();
        $countries = Country::select('id', 'name')->orderBy('name')->get();
        $sports = Sport::orderBy('name')->get()->map(fn ($sport) => [
            'id' => $sport->id,
            'name' => $sport->translated_name,
        ]);
        $professional_roles = ProfessionalRole::select('id', 'name')->orderBy('name')->get();
        $cmas_zones = GeoZone::select('id', 'name')->orderBy('name')->get();
        $entities = Entity::select('id', 'name')->orderBy('name')->get();

        // Fetch licenses for the dropdown based on committee and international flag
        $licensesQuery = License::select('id', 'name')
            ->whereHas('committee', function ($q) use ($committee, $isInternational) {
                $q->where('code', $committee)
                    ->where('is_international', $isInternational);
            });

        if ($isInternational) {
            $licensesQuery->withoutGlobalScope(ExcludeInternationalScope::class);
        }

        $availableLicenses = $licensesQuery->orderBy('name')->get();

        $filter_status = [
            'active' => ['id' => 'active', 'name' => __('licenses.state_active')],
            'pending' => ['id' => 'pending', 'name' => __('licenses.state_pending')],
            'canceled' => ['id' => 'canceled', 'name' => __('licenses.state_canceled')],
            'provisional' => ['id' => 'provisional', 'name' => __('licenses.state_provisional')],
            'suspended' => ['id' => 'suspended', 'name' => __('licenses.state_suspended')],
            'waiting_approval' => ['id' => 'waiting_approval', 'name' => __('licenses.state_waiting_approval')],
            'expired' => ['id' => 'expired', 'name' => __('licenses.state_expired')],
        ];

        $filter_payment_status = [
            'paid' => ['id' => 'paid', 'name' => __('licenses.payment_status_paid')],
            'pending_payment' => ['id' => 'pending_payment', 'name' => __('licenses.payment_status_pending_payment')],
            'no_document' => ['id' => 'no_document', 'name' => __('licenses.payment_status_no_document')],
        ];

        return view('web.admin.license_attributed.separated', compact(
            'licenses',
            'committee',
            'isInternational',
            'holderType',
            'filter_status',
            'filter_payment_status',
            'federations',
            'countries',
            'sports',
            'professional_roles',
            'cmas_zones',
            'entities',
            'availableLicenses',
            'pageTitle',
            'pageSubtitle'
        ));
    }
}
