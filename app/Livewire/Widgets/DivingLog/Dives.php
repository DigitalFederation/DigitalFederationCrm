<?php

namespace App\Livewire\Widgets\DivingLog;

use Domain\DivingLogs\Models\DivingLog;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dives extends Component
{
    public int $dives;

    public function render(): View
    {
        try {
            $this->dives = $this->findDives();

            return view('livewire.widgets.diving-log.dives');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return view('livewire.widgets.404')->with('title', 'Dives');
        }
    }

    public function findDives(): int
    {
        $individualId = auth()->user()->individual->id;

        // Define a unique cache key based on the individual's ID
        $cacheKey = 'dives_count_for_individual_' . $individualId;
        $cacheDuration = now()->addMinutes(60);

        return cache()->remember($cacheKey, $cacheDuration, function () use ($individualId) {
            return DivingLog::where('individual_id', $individualId)->count();
        });
    }
}
