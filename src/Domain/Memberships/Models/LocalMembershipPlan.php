<?php

namespace Domain\Memberships\Models;

use Illuminate\Database\Eloquent\Model;

class LocalMembershipPlan extends Model
{
    protected $table = 'local_membership_plan_associations';

    protected $fillable = [
        'local_federation_id',
        'membership_plan_id',
    ];

    public function membershipPlan()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }
}
