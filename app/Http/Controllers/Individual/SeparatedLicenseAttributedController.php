<?php

namespace App\Http\Controllers\Individual;

use App\Enums\CommitteeCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\Sport;
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
     * Sport professional licenses (Arbitros e Treinadores)
     * Committee: SPORT, International: false
     */
    public function sport(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::Sport->value,
            false,
            __('licenses.individual_sport_licenses_title'),
            __('licenses.individual_sport_licenses_subtitle')
        );
    }

    /**
     * Primary federation diving professional licenses.
     * Committee: DIVINGSERVICES, International: false
     */
    public function nationalDiving(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::DivingServices->value,
            false,
            __('licenses.individual_national_diving_licenses_title'),
            __('licenses.individual_national_diving_licenses_subtitle')
        );
    }

    /**
     * International recreational diving professional licenses.
     * Committee: DIVING, International: true
     */
    public function divingCmas(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::Diving->value,
            true,
            __('licenses.individual_cmas_diving_licenses_title'),
            null
        );
    }

    /**
     * International scientific diving professional licenses.
     * Committee: SCIENTIFIC, International: true
     */
    public function scientific(): View
    {
        return $this->renderLicensesPage(
            CommitteeCodeEnum::Scientific->value,
            true,
            __('licenses.individual_scientific_licenses_title'),
            null
        );
    }

    /**
     * Render the licenses attributed page with fixed committee and international parameters
     */
    private function renderLicensesPage(
        string $committee,
        bool $isInternational,
        string $pageTitle,
        ?string $pageSubtitle = null
    ): View {
        $individual = Auth::user()->individual;

        if (! $individual) {
            abort(403, __('No individual profile associated with this user'));
        }

        $query = QueryBuilder::for(LicenseAttributed::class)
            ->with(['owner', 'license', 'license.committee', 'license.sport', 'license.professionalRole'])
            ->allowedFilters([
                AllowedFilter::scope('filter_expiration_end', 'expiration_after'),
                AllowedFilter::scope('filter_expiration_start', 'expiration_before'),
                AllowedFilter::scope('filter_cmas_code', 'cmas_code'),
                AllowedFilter::scope('filter_sport', 'sport'),
                AllowedFilter::scope('filter_category', 'professionalRole'),
                AllowedFilter::scope('filter_name', 'license_name'),
                AllowedFilter::scope('filter_status', 'license_attributed_status'),
            ]);

        // Filter by individual
        $query->where('model_type', 'individual')
            ->where('model_id', $individual->id);

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
            'active' => ['id' => 'active', 'name' => __('licenses.state_active')],
            'pending' => ['id' => 'pending', 'name' => __('licenses.state_pending')],
            'canceled' => ['id' => 'canceled', 'name' => __('licenses.state_canceled')],
        ];

        return view('web.individual.licenses-attributed.separated', compact(
            'licenses',
            'committee',
            'filter_status',
            'sports',
            'professional_roles',
            'isInternational',
            'pageTitle',
            'pageSubtitle'
        ));
    }
}
