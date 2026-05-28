<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class DivingLogSearchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::user()->group()->first()->code == 'INDIVIDUAL') {
            $individualLogged = Auth::user()->individuals()->first();

            $builder->where('individual_id', $individualLogged->id)
                ->orWhereHas('buddies', function (Builder $query) use ($individualLogged) {
                    $query->where('individual_id', $individualLogged->id);
                })
                ->orWhereHas('validation', function (Builder $query) use ($individualLogged) {
                    $query->where('individual_id', $individualLogged->id);
                });
        }
    }
}
