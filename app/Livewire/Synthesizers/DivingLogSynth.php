<?php

namespace App\Livewire\Synthesizers;

use Domain\DivingLogs\Models\DivingLog;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class DivingLogSynth extends Synth
{
    public static $key = 'divingLog';

    public static function match($target)
    {
        return $target instanceof DivingLog;
    }

    public function dehydrate($target)
    {
        return [$target->attributesToArray(), []];
    }

    public function hydrate($value)
    {
        parent::hydrate();

        $model = new DivingLog;
        $model->setRawAttributes($value, true);

        dd($model);

        return $model;
    }
}
