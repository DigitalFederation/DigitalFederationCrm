<?php

namespace App\Http\Controllers\Entity;

use App\Enums\CommitteeCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\Sport;
use Domain\Individuals\Models\IndividualEntity;
use Domain\Individuals\Models\ProfessionalRole;
use Domain\Licenses\Models\LicenseAttributed;
use Domain\Licenses\Scopes\ExcludeInternationalScope;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SeparatedLicenseAttributedController extends Controller
{
    /**
     * Sport entity licenses.
     * Committee: SPORT, International: false
     */
    public function sportEntity(): View
    {
        return $this->renderLicensesPage('entity', CommitteeCodeEnum::Sport->value, false, __('licenses.Sport Club Licenses'));
    }

    /**
     * Sport member licenses.
     * Committee: SPORT, International: false
     */
    public function sportMembers(): View
    {
        return $this->renderLicensesPage('members', CommitteeCodeEnum::Sport->value, false, __('licenses.Sport Licenses'));
    }

    /**
     * International diving entity licenses.
     * Committee: DIVING, International: true
     */
    public function divingCmasEntity(): View
    {
        return $this->renderLicensesPage('entity', CommitteeCodeEnum::Diving->value, true, __('licenses.International Entity Licenses'));
    }

    /**
     * International diving member licenses.
     * Committee: DIVING, International: true
     */
    public function divingCmasMembers(): View
    {
        return $this->renderLicensesPage('members', CommitteeCodeEnum::Diving->value, true, __('licenses.International Professional Licenses'));
    }

    /**
     * Scientific entity licenses (Mergulho Cientifico CMAS)
     * Committee: SCIENTIFIC, International: true
     */
    public function scientificEntity(): View
    {
        return $this->renderLicensesPage('entity', CommitteeCodeEnum::Scientific->value, true, __('licenses.CMAS Scientific Entity Licenses'));
    }

    /**
     * Scientific member licenses (Mergulho Cientifico CMAS)
     * Committee: SCIENTIFIC, International: true
     */
    public function scientificMembers(): View
    {
        return $this->renderLicensesPage('members', CommitteeCodeEnum::Scientific->value, true, __('licenses.CMAS Scientific Professional Licenses'));
    }

    /**
     * Primary federation diving service member licenses.
     * Committee: DIVINGSERVICES, International: false (national)
     */
    public function nationalDivingMembers(): View
    {
        return $this->renderLicensesPage('members', CommitteeCodeEnum::DivingServices->value, false, __('licenses.Primary Diving Services Licenses'));
    }

    /**
     * Render the licenses attributed page with fixed committee and international parameters
     */
    private function renderLicensesPage(string $type, string $committee, bool $isInternational, string $pageTitle): View
    {
        $entity = Auth::user()->getEntity();

        if (! $entity) {
            abort(403, __('No entity associated with this user'));
        }

        $query = QueryBuilder::for(LicenseAttributed::class)
            ->with(['owner', 'license', 'license.committee', 'license.sport', 'license.professionalRole'])
            ->allowedFilters([
                AllowedFilter::scope('filter_holder_type', 'holder_type'),
                AllowedFilter::scope('filter_expiration_end', 'expiration_after'),
                AllowedFilter::scope('filter_expiration_start', 'expiration_before'),
                AllowedFilter::scope('filter_cmas_code', 'cmas_code'),
                AllowedFilter::scope('filter_sport', 'sport'),
                AllowedFilter::scope('filter_category', 'professionalRole'),
                AllowedFilter::scope('filter_name', 'license_name'),
                AllowedFilter::scope('filter_status', 'license_attributed_status'),
                AllowedFilter::scope('filter_professional'),
            ]);

        if ($type === 'entity') {
            // Entity licenses
            $query->where('model_type', 'entity')
                ->where('model_id', $entity->id);
        } else {
            // Member licenses - get individuals associated with entity
            $individualIds = IndividualEntity::where('entity_id', $entity->id)
                ->pluck('individual_id');

            $query->where('model_type', 'individual')
                ->whereIn('model_id', $individualIds);
        }

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

        $licenses = $query->paginate()
            ->appends(request()->query());

        $sports = Sport::select('id', 'name')->orderBy('name')->get();
        $professional_roles = ProfessionalRole::select('id', 'name')->orderBy('name')->get();

        $filter_status = [
            'active' => ['id' => 'active', 'name' => __('Active')],
            'pending' => ['id' => 'pending', 'name' => __('Pending')],
            'canceled' => ['id' => 'canceled', 'name' => __('Suspended')],
        ];

        // Set the holder type for the view
        $filterHolderType = $type === 'entity' ? 'entity' : 'individual';

        return view('web.entity.license_attributed.index', compact(
            'licenses',
            'committee',
            'filter_status',
            'sports',
            'professional_roles',
            'filterHolderType',
            'isInternational',
            'type',
            'pageTitle'
        ));
    }
}
