<?php

namespace Domain\Federations\Actions;

use Illuminate\Support\Collection;

class GetCountriesByFederationAction
{
    public static function execute(): Collection
    {
        $countries = collect([]);
        $federations = \Auth::user()->federations;
        foreach ($federations as $federation) {
            $countries->push($federation->country);
        }

        return $countries;

    }

}
