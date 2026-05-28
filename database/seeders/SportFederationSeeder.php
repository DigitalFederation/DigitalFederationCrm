<?php

namespace Database\Seeders;

use App\Models\Committee;
use Carbon\Carbon;
use Domain\Federations\Models\Federation;
use Domain\Memberships\Models\Membership;
use Domain\Memberships\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class SportFederationSeeder extends Seeder
{
    /**
     * Sport Federation - Federação com membership ativa do plano com committee sport
     *
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(UserGroupSeeder::class);
        $this->call(CommitteeSeeder::class);

        $federation = Federation::factory()->create();

        $plan = MembershipPlan::factory()->create([
            'committee_id' => Committee::where('code', 'SPORT')->first()->id,
        ]);

        $date = Carbon::now();

        Membership::factory()->create([
            'federation_id' => $federation->id,
            'plan_id' => $plan->id,
            'activated_at' => $date->format('Y-m-d H:i:s'),
            'current_term_starts_at' => $date->format('Y-m-d'),
            'current_term_ends_at' => $date->add($plan->interval, $plan->interval_unit)->format('Y-m-d'),
        ]);

    }
}
