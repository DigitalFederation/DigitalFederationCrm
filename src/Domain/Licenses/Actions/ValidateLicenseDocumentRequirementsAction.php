<?php

namespace Domain\Licenses\Actions;

use Domain\Entities\Models\Entity;
use Domain\Individuals\Models\Individual;
use Domain\Licenses\Models\License;
use Domain\OfficialDocuments\Models\OfficialDocument;
use Domain\OfficialDocuments\States\ActiveOfficialDocumentState;

class ValidateLicenseDocumentRequirementsAction
{
    private array $errors = [];

    /**
     * Validate if the owner (Individual or Entity) has all required documents for a license
     *
     * @param  License  $license  The license to check requirements for
     * @param  Individual|Entity  $owner  The owner requesting the license
     * @return array ['is_valid' => bool, 'errors' => array, 'missing_documents' => array]
     */
    public function __invoke(License $license, $owner): array
    {
        $this->errors = [];
        $missingDocuments = [];

        // Entities do NOT require documents - document requirements only apply to Individuals
        if ($owner instanceof Entity) {
            return [
                'is_valid' => true,
                'errors' => [],
                'missing_documents' => [],
            ];
        }

        // If license doesn't require documents, it's valid
        if (! $license->requires_official_documents || empty($license->required_document_types)) {
            return [
                'is_valid' => true,
                'errors' => [],
                'missing_documents' => [],
            ];
        }

        // Determine owner type
        $ownerType = match (true) {
            $owner instanceof Individual => 'Domain\Individuals\Models\Individual',
            $owner instanceof Entity => 'Domain\Entities\Models\Entity',
            default => null
        };

        if (! $ownerType) {
            $this->errors[] = [
                'code' => 'INVALID_OWNER_TYPE',
                'message' => __('validation.invalid_owner_type'),
            ];

            return [
                'is_valid' => false,
                'errors' => $this->errors,
                'missing_documents' => [],
            ];
        }

        // Check each required document type
        foreach ($license->required_document_types as $documentType) {
            $hasActiveDocument = OfficialDocument::where('owner_type', $ownerType)
                ->where('owner_id', $owner->id)
                ->where('type', $documentType)
                ->where('status_class', ActiveOfficialDocumentState::class)
                ->where(function ($query) {
                    $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>', now());
                })
                ->exists();

            if (! $hasActiveDocument) {
                $missingDocuments[] = $documentType;
                $this->errors[] = [
                    'code' => 'MISSING_REQUIRED_DOCUMENT',
                    'document_type' => $documentType,
                    'message' => __('validation.missing_required_document', [
                        'document' => \App\Enums\OfficialDocumentTypeEnum::toString($documentType),
                    ]),
                ];
            }
        }

        return [
            'is_valid' => empty($this->errors),
            'errors' => $this->errors,
            'missing_documents' => $missingDocuments,
        ];
    }
}
