<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Group;
use App\Models\User;
use Domain\Federations\Models\Federation;
use Illuminate\Database\Seeder;

class FederationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvDiverFile = fopen(base_path('database/data/federations.csv'), 'r');

        $firstline = true;

        $federations = [];
        $users = [];
        $countries = Country::select('id', 'ioc')->get()->toArray();

        while (($data = fgetcsv($csvDiverFile, 2000, ';')) !== false) {
            if (! $firstline) {

                $country_key = array_search($data['3'], array_column($countries, 'ioc'));

                $federations[] = [
                    'id' => $data['0'],
                    'country_id' => $countries[$country_key]['id'],
                    'parent_id' => ! empty($data['7']) ? $data['7'] : null,
                    'name' => $data['8'],
                    'is_local' => $data['9'],
                    'legal_name' => $data['10'],
                    'address' => $data['11'],
                    'location' => $data['12'],
                    'website' => $data['13'],
                    'email' => $data['14'],
                    'phone' => $data['15'],
                    'board_members' => ! empty($data['16']) ? $data['16'] : null,
                    'code_cmas' => $data['4'],
                ];

                $user_email = ! empty($data['14']) ? $data['14'] : $this->slugify($data['8']).'@example.test';

                $users[] = User::factory([
                    'name' => $data['8'],
                    'email' => $user_email,
                    'group_id' => Group::select('id')->where('code', 'FEDERATION')->pluck('id')->first(),
                ])->make()->toArray();
            }
            $firstline = false;
        }

        for ($i = 0; $i < count($federations); $i++) {
            User::factory()->create($users[$i]);
            Federation::create($federations[$i])->users()->sync($users[$i]['id']);
        }

        fclose($csvDiverFile);
    }

    private function slugify($string): string
    {
        $string = str_replace(['Ö', 'É', 'À', 'Ç', 'Ê', 'Ï'], ['o', 'e', 'a', 'c', 'e', 'i'], $string);

        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $string), '-'));
    }
}
