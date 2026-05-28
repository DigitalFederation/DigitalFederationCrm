<?php

namespace Database\Seeders;

use App\Models\Committee;
use Domain\Certifications\Models\Certification;
use Domain\Individuals\Models\ProfessionalRole;
use Domain\Licenses\Models\License;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $csvDiverFile = fopen(base_path('database/data/cmas_certifications_diving_initial.csv'), 'r');

        $firstline = true;

        $certifications = [];
        while (($data = fgetcsv($csvDiverFile, 2000, ';')) !== false) {
            if (! $firstline) {

                $certifications[] = [
                    'committee_id' => ! empty($data['0']) ? $this->getCommitteeId($data['0']) : null,
                    'professional_role_id' => ! empty($data['1']) ? $this->getTypeId($data['1']) : null,
                    // 'parent_id' => null,
                    'name' => $data['4'],
                    'license_id' => ! empty($data['3']) ? $this->getLicenseId($data['3']) : null,
                ];
            }
            $firstline = false;
        }

        Certification::insert($certifications);

        fclose($csvDiverFile);

        $csvDiverFile2 = fopen(base_path('database/data/cmas_certifications_diving_initial.csv'), 'r');
        $firstline1 = true;
        // now the parent_id
        while (($data2 = fgetcsv($csvDiverFile2, 2000, ';')) !== false) {
            if (! $firstline1) {
                $certification = Certification::where('name', $data2['4'])->first();
                // $certification->parent_id = !empty($data2['2'])?$this->getCertificationId($data2['2']):null;
                $certification->save();
            }
            $firstline1 = false;
        }
        fclose($csvDiverFile2);

        $csvSportFile = fopen(base_path('database/data/cmas_certifications_sport_initial.csv'), 'r');
        $certifications2 = [];
        $firstline3 = true;
        while (($data = fgetcsv($csvSportFile, 2000, ';')) !== false) {
            if (! $firstline3) {

                $certifications2[] = [
                    'committee_id' => Committee::select('id')->where('code', 'SPORT')->pluck('id')->first(),
                    'professional_role_id' => ! empty($data['1']) ? $this->getTypeId($data['1']) : null,
                    // 'parent_id' => null,
                    'name' => $data['4'],
                    'license_id' => ! empty($data['3']) ? $this->getLicenseId($data['3']) : null,
                ];
            }
            $firstline3 = false;
        }

        Certification::insert($certifications2);
    }

    private function getLicenseId($name)
    {
        $license = License::where('name', 'LIKE', '%'.$name.'%')->first();

        return ! empty($license->id) ? $license->id : null;
    }

    private function getCommitteeId($name)
    {
        $license = Committee::where('name', 'LIKE', '%'.$name.'%')->first();

        return $license->id;
    }

    private function getTypeId($name)
    {
        $type = ProfessionalRole::where('name', 'LIKE', '%'.trim($name).'%')->first();

        return ! empty($type->id) ? $type->id : null;
    }

    private function getCertificationId($name)
    {
        $certification = Certification::where('name', $name)->first();

        return ! empty($certification->id) ? $certification->id : null;
    }
}
