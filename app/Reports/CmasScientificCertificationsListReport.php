<?php

namespace App\Reports;

use Domain\Certifications\Models\CertificationAttributed;
use Illuminate\Support\Collection;

class CmasScientificCertificationsListReport implements ReportTemplate
{
    public static function getDisplayName(): string
    {
        return __('reports.cmas_scientific_certifications_list');
    }

    public function query($filters)
    {
        $query = CertificationAttributed::query()
            ->with([
                'individual',
                'certification.committee',
                'entity',
                'mainInstructor',
            ])
            ->whereHas('certification.committee', fn ($q) => $q->where('code', 'SCIENTIFIC'));

        if (! empty($filters['start_date'])) {
            $query->whereDate('certification_attributed.created_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $query->whereDate('certification_attributed.created_at', '<=', $filters['end_date']);
        }

        return $query;
    }

    public function processData($data)
    {
        if (! $data instanceof Collection) {
            $data = collect($data);
        }

        return $data->map(function (CertificationAttributed $certAttributed) {
            $individual = $certAttributed->individual;

            $fullName = $individual
                ? trim($individual->name . ' ' . ($individual->surname ?? ''))
                : __('reports.not_available');

            $birthDate = $individual?->birthdate
                ? \Carbon\Carbon::parse($individual->birthdate)->format('d/m/Y')
                : __('reports.not_available');

            $gender = $individual?->gender ?? __('reports.not_available');

            $memberNumber = $individual?->member_number ?? __('reports.not_available');

            $certificationName = $certAttributed->certification?->name ?? __('reports.not_available');

            $issueDate = $certAttributed->current_term_starts_at
                ? $certAttributed->current_term_starts_at->format('d/m/Y')
                : __('reports.not_available');

            $expiryDate = $certAttributed->current_term_ends_at
                ? $certAttributed->current_term_ends_at->format('d/m/Y')
                : __('reports.not_available');

            $mainInstructor = $certAttributed->mainInstructor->first();
            $courseDirector = $mainInstructor
                ? trim($mainInstructor->name . ' ' . ($mainInstructor->surname ?? ''))
                : __('reports.not_available');

            $school = $certAttributed->entity?->name
                ?? $certAttributed->entity_name
                ?? __('reports.not_available');

            $email = $individual?->email ?? __('reports.not_available');
            $phone = $individual?->phone ?? __('reports.not_available');

            return [
                __('reports.columns.full_name') => $fullName,
                __('reports.columns.birth_date') => $birthDate,
                __('reports.columns.gender') => $gender,
                __('reports.columns.member_number') => $memberNumber,
                __('reports.columns.certification_name') => $certificationName,
                __('reports.columns.certification_number') => $certAttributed->code ?? __('reports.not_available'),
                __('reports.columns.issue_date') => $issueDate,
                __('reports.columns.expiry_date') => $expiryDate,
                __('reports.columns.course_director') => $courseDirector,
                __('reports.columns.school') => $school,
                __('reports.columns.email') => $email,
                __('reports.columns.phone') => $phone,
            ];
        });
    }

    public function columns(): array
    {
        return [
            __('reports.columns.full_name'),
            __('reports.columns.birth_date'),
            __('reports.columns.gender'),
            __('reports.columns.member_number'),
            __('reports.columns.certification_name'),
            __('reports.columns.certification_number'),
            __('reports.columns.issue_date'),
            __('reports.columns.expiry_date'),
            __('reports.columns.course_director'),
            __('reports.columns.school'),
            __('reports.columns.email'),
            __('reports.columns.phone'),
        ];
    }
}
