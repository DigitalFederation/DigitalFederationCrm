<?php

namespace App\Http\Controllers\Individual;

use App\Enums\CommitteeCodeEnum;
use App\Http\Controllers\Controller;
use Domain\Federations\Models\Federation;
use Domain\Individuals\Models\Individual;
use Domain\Licenses\Actions\CalculateLicensePriceAction;
use Domain\Licenses\Actions\CreateLicenseAttributedAction;
use Domain\Licenses\Actions\PurchaseLicenseAction;
use Domain\Licenses\Models\License;
use Domain\Memberships\Services\ValidationPlanPrivilegeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicensePurchaseController extends Controller
{
    /**
     * Sport professional license purchase (referees and coaches)
     */
    public function sport()
    {
        return $this->renderPurchasePage(
            CommitteeCodeEnum::Sport->value,
            false,
            __('licenses.individual_sport_license_title'),
            __('licenses.individual_sport_license_subtitle')
        );
    }

    /**
     * Primary federation diving professional license purchase.
     */
    public function nationalDiving()
    {
        return $this->renderPurchasePage(
            CommitteeCodeEnum::DivingServices->value,
            false,
            __('licenses.individual_national_diving_license_title'),
            __('licenses.individual_national_diving_license_subtitle')
        );
    }

    /**
     * International recreational diving professional license purchase.
     */
    public function divingInternational()
    {
        return $this->renderPurchasePage(
            CommitteeCodeEnum::Diving->value,
            true,
            __('licenses.individual_cmas_diving_license_title'),
            __('licenses.individual_cmas_diving_license_subtitle')
        );
    }

    /**
     * International scientific diving professional license purchase.
     */
    public function scientific()
    {
        return $this->renderPurchasePage(
            CommitteeCodeEnum::Scientific->value,
            true,
            __('licenses.individual_scientific_license_title'),
            __('licenses.individual_scientific_license_subtitle')
        );
    }

    /**
     * Shared render method for all separated license purchase pages
     */
    private function renderPurchasePage(
        string $committee,
        bool $isInternational,
        string $pageTitle,
        string $pageSubtitle
    ) {
        $individual = Auth::user()->individual;

        if (! $individual) {
            abort(403, 'No individual profile associated with this user');
        }

        $federation = Federation::where('is_default_federation', true)->first();

        return view('web.individual.license-purchase.index', [
            'individual' => $individual,
            'federation' => $federation,
            'committee' => $committee,
            'isInternational' => $isInternational,
            'pageTitle' => $pageTitle,
            'pageSubtitle' => $pageSubtitle,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'license_id' => 'required|exists:license,id',
            'individual_id' => 'required|exists:individual,id',
        ]);

        // Verify user owns this individual
        $individual = Individual::find($request->individual_id);
        if (! $individual || $individual->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $license = License::find($request->license_id);

        $purchaseAction = new PurchaseLicenseAction(
            new CreateLicenseAttributedAction,
            new CalculateLicensePriceAction,
            new ValidationPlanPrivilegeService
        );

        try {
            $licenseAttributed = $purchaseAction($license, $individual);

            return redirect()
                ->route('individual.license-purchase.success')
                ->with('success', __('License purchase initiated. Please complete payment to activate your license.'))
                ->with('license_attributed_id', $licenseAttributed->id);

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function success()
    {
        $licenseAttributedId = session('license_attributed_id');

        if (! $licenseAttributedId) {
            return redirect()->route('individual.sport-license-purchase.index')
                ->with('error', 'No license purchase found.');
        }

        $licenseAttributed = \Domain\Licenses\Models\LicenseAttributed::with(['license.professionalRole', 'license.sport', 'owner'])
            ->find($licenseAttributedId);

        if (! $licenseAttributed) {
            return redirect()->route('individual.sport-license-purchase.index')
                ->with('error', 'License purchase not found.');
        }

        $license = $licenseAttributed->license;

        // Get the document (invoice) created for this license
        $individual = $licenseAttributed->owner;
        $document = null;

        if ($individual && $licenseAttributed) {
            // Find document through document_details relationship
            $document = \Domain\Documents\Models\Document::where('owner_type', 'individual')
                ->where('owner_id', $individual->id)
                ->whereHas('type', function ($query) {
                    $query->where('code', 'ORD');
                })
                ->whereHas('details', function ($query) use ($licenseAttributed) {
                    $query->where('owner_type', \Domain\Licenses\Models\LicenseAttributed::class)
                        ->where('owner_id', $licenseAttributed->id);
                })
                ->latest()
                ->first();
        }

        return view('web.individual.license-purchase.success', compact('licenseAttributed', 'license', 'document', 'individual'));
    }
}
