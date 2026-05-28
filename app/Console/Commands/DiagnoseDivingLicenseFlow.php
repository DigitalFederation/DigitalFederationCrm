<?php

namespace App\Console\Commands;

use Domain\Licenses\Models\LicenseAttributed;
use Domain\Licenses\States\PendingLicenseAttributedState;
use Domain\Licenses\States\PendingTechnicalDirectorApprovalLicenseAttributedState;
use Domain\Licenses\States\PendingValidationLicenseAttributedState;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DiagnoseDivingLicenseFlow extends Command
{
    protected $signature = 'diagnose:diving-licenses';

    protected $description = 'Diagnose diving license flow issues - check committee configuration and license states';

    public function handle(): int
    {
        $this->info('=== COMMITTEE CONFIGURATION ===');
        $committees = DB::table('committee')->get();

        $this->table(
            ['ID', 'Code', 'Name', 'Is International'],
            $committees->map(fn ($c) => [$c->id, $c->code, $c->name, $c->is_international ? 'YES' : 'NO'])
        );

        $this->newLine();
        $this->info('=== EXPECTED BEHAVIOR ===');
        $this->line('SPORT (is_international=0): Requires admin validation if configured');
        $this->line('SCIENTIFIC (is_international=1): Skip admin validation, go directly to payment');
        $this->line('DIVING (is_international=1): Skip admin validation, go directly to payment');
        $this->line('DIVINGSERVICES (is_international=0): TD approval (entities) + Admin validation');

        $this->newLine();
        $this->info('=== LICENSE COUNTS BY COMMITTEE AND STATE ===');

        $stats = DB::table('license_attributed as la')
            ->join('license as l', 'la.license_id', '=', 'l.id')
            ->join('committee as c', 'l.committee_id', '=', 'c.id')
            ->whereNull('la.deleted_at')
            ->select(
                'c.code as committee_code',
                'c.is_international',
                'la.status_class',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('c.code', 'c.is_international', 'la.status_class')
            ->orderBy('c.code')
            ->orderBy('la.status_class')
            ->get();

        $this->table(
            ['Committee', 'Is International', 'State', 'Count'],
            $stats->map(fn ($s) => [
                $s->committee_code,
                $s->is_international ? 'YES' : 'NO',
                class_basename($s->status_class),
                $s->count,
            ])
        );

        $this->newLine();
        $this->info('=== POTENTIAL ISSUES ===');

        // Check for DIVING licenses in TD approval (wrong - international should skip TD)
        $divingInTdApproval = DB::table('license_attributed as la')
            ->join('license as l', 'la.license_id', '=', 'l.id')
            ->join('committee as c', 'l.committee_id', '=', 'c.id')
            ->where('c.code', 'DIVING')
            ->where('c.is_international', true)
            ->where('la.status_class', PendingTechnicalDirectorApprovalLicenseAttributedState::class)
            ->whereNull('la.deleted_at')
            ->count();

        if ($divingInTdApproval > 0) {
            $this->error("ISSUE: {$divingInTdApproval} DIVING (international) licenses in TD Approval state - should skip to Payment!");
        } else {
            $this->info('OK: No DIVING licenses incorrectly in TD Approval state');
        }

        // Check for DIVING licenses in Pending Validation (wrong - international should skip admin validation)
        $divingInPendingValidation = DB::table('license_attributed as la')
            ->join('license as l', 'la.license_id', '=', 'l.id')
            ->join('committee as c', 'l.committee_id', '=', 'c.id')
            ->where('c.code', 'DIVING')
            ->where('c.is_international', true)
            ->where('la.status_class', PendingValidationLicenseAttributedState::class)
            ->whereNull('la.deleted_at')
            ->count();

        if ($divingInPendingValidation > 0) {
            $this->error("ISSUE: {$divingInPendingValidation} DIVING (international) licenses in Pending Validation state - should skip to Payment!");
        } else {
            $this->info('OK: No DIVING licenses incorrectly in Pending Validation state');
        }

        // Check for licenses in Pending state without documents
        $pendingWithoutDocs = DB::table('license_attributed as la')
            ->leftJoin('document_detail as dd', function ($join) {
                $join->on('dd.owner_id', '=', 'la.id')
                    ->where('dd.owner_type', '=', LicenseAttributed::class);
            })
            ->where('la.status_class', PendingLicenseAttributedState::class)
            ->where('la.total_value', '>', 0)
            ->whereNull('dd.id')
            ->whereNull('la.deleted_at')
            ->count();

        if ($pendingWithoutDocs > 0) {
            $this->error("ISSUE: {$pendingWithoutDocs} licenses in Pending (payment) state without payment documents!");
        } else {
            $this->info('OK: All pending licenses have payment documents');
        }

        // Check committee is_international flags
        $divingCommittee = $committees->firstWhere('code', 'DIVING');
        $divingServicesCommittee = $committees->firstWhere('code', 'DIVINGSERVICES');

        if ($divingCommittee && ! $divingCommittee->is_international) {
            $this->error('ISSUE: DIVING committee has is_international=FALSE - should be TRUE!');
        }

        if ($divingServicesCommittee && $divingServicesCommittee->is_international) {
            $this->error('ISSUE: DIVINGSERVICES committee has is_international=TRUE - should be FALSE!');
        }

        $this->newLine();
        $this->info('=== LICENSES BY NAME AND COMMITTEE (check for mismatches) ===');

        $licensesByCommittee = DB::table('license as l')
            ->join('committee as c', 'l.committee_id', '=', 'c.id')
            ->whereNull('l.deleted_at')
            ->where(function ($q) {
                $q->where('l.name', 'like', '%CMAS%')
                    ->orWhere('l.name', 'like', '%Centro de Mergulho%')
                    ->orWhere('l.name', 'like', '%Escola de Mergulho%')
                    ->orWhere('l.name', 'like', '%Instrutor%')
                    ->orWhere('l.name', 'like', '%Divemaster%');
            })
            ->select('l.id', 'l.name', 'c.code as committee_code', 'c.is_international')
            ->orderBy('c.code')
            ->orderBy('l.name')
            ->get();

        $this->table(
            ['License ID', 'License Name', 'Committee', 'Is International'],
            $licensesByCommittee->map(fn ($l) => [
                $l->id,
                substr($l->name, 0, 45),
                $l->committee_code,
                $l->is_international ? 'YES' : 'NO',
            ])
        );

        // Check for potential mismatches
        foreach ($licensesByCommittee as $license) {
            $hasCmas = stripos($license->name, 'CMAS') !== false;
            if ($hasCmas && $license->committee_code === 'DIVINGSERVICES') {
                $this->error("MISMATCH: License '{$license->name}' has CMAS in name but is in DIVINGSERVICES committee!");
            }
            if (! $hasCmas && $license->committee_code === 'DIVING') {
                $this->warn("CHECK: License '{$license->name}' has no CMAS in name but is in DIVING (international) committee");
            }
        }

        $this->newLine();
        $this->info('=== DIVING SERVICES LICENSES IN PENDING VALIDATION ===');
        $divingServicesPendingValidation = DB::table('license_attributed as la')
            ->join('license as l', 'la.license_id', '=', 'l.id')
            ->join('committee as c', 'l.committee_id', '=', 'c.id')
            ->where('c.code', 'DIVINGSERVICES')
            ->where('la.status_class', PendingValidationLicenseAttributedState::class)
            ->whereNull('la.deleted_at')
            ->select('la.id', 'la.license_name', 'la.holder_name', 'la.total_value')
            ->limit(10)
            ->get();

        if ($divingServicesPendingValidation->isNotEmpty()) {
            $this->table(
                ['ID', 'License', 'Holder', 'Value'],
                $divingServicesPendingValidation->map(fn ($l) => [$l->id, $l->license_name, $l->holder_name, $l->total_value])
            );
            $this->line('These can be approved by admin and should generate payment documents.');
        } else {
            $this->line('No DIVINGSERVICES licenses pending admin validation.');
        }

        return Command::SUCCESS;
    }
}
