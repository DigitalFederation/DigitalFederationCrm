<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Domain\Certifications\Models\CertificationAttributed;
use Domain\Individuals\Models\Individual;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificationAttributedApiController extends Controller
{
    public function show(Request $request, $code_cmas): JsonResponse
    {

        // Fetch the individual
        $individual = Individual::where('code_cmas', $code_cmas)->first();
        if (! $individual) {
            return response()->json(['error' => 'Individual not found'], 404);
        }

        // Fetch certifications
        $certifications = CertificationAttributed::where('individual_id', $individual->id)
            ->with([
                'certification:id,name,committee_id',
                'certification.committee:id,code',
                'federation:id,name',
                'federation.country:id,name,iso',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Organize certifications by committee
        $certificationsByCommittee = [
            'diving' => $certifications->filter(fn ($cert) => $cert->certification->committee->code === 'DIVING'),
            'scientific' => $certifications->filter(fn ($cert) => $cert->certification->committee->code === 'SCIENTIFIC'),
            'sport' => $certifications->filter(fn ($cert) => $cert->certification->committee->code === 'SPORT'),
        ];

        // Return the organized data
        return response()->json([
            'individual' => [
                'full_name' => $individual->full_name,
                'code_cmas' => $individual->code_cmas,
            ],
            'certifications_by_committee' => $certificationsByCommittee,
        ]);
    }
}
