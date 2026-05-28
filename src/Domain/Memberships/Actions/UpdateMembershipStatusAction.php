<?php

namespace Domain\Memberships\Actions;

use Carbon\Carbon;
use Domain\Memberships\Models\MemberSubscription;
use Domain\Memberships\States\ActiveMemberSubscriptionState;
use Domain\Memberships\States\ExpiredMemberSubscriptionState;

class UpdateMembershipStatusAction
{
    public function __invoke(MemberSubscription $subscription): void
    {
        $now = Carbon::now();
        $affiliationValid = $subscription->affiliation->end_date->gt($now);
        $insuranceValid = $subscription->insurance->end_date->gt($now);

        if ($affiliationValid && $insuranceValid && $subscription->end_date->gt($now)) {
            $subscription->status_class = ActiveMemberSubscriptionState::class;
        } else {
            $subscription->status_class = ExpiredMemberSubscriptionState::class;
        }

        $subscription->save();
    }
}
