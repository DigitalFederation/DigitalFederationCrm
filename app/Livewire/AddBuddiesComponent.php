<?php

namespace App\Livewire;

use Domain\DivingLogs\Models\DivingBuddy;
use Livewire\Component;

class AddBuddiesComponent extends Component
{
    public $divingLogId;

    public $buddies;

    public $currentBuddies;

    public function mount($divingLogId)
    {
        $this->divingLogId = $divingLogId;
        $this->buddies = DivingBuddy::where('individual_id', auth()->user()->individuals()->first()->id)->get();
        $this->loadCurrentBuddies();
    }

    public function addBuddy($buddyId)
    {
        $buddy = DivingBuddy::findOrFail($buddyId);
        $buddy->diving_logs()->attach($this->divingLogId);
        $this->buddies = DivingBuddy::where('individual_id', auth()->user()->individuals()->first()->id)->get();
        $this->loadCurrentBuddies();
    }

    public function removeBuddy($buddyId)
    {
        $buddy = DivingBuddy::findOrFail($buddyId);
        $buddy->diving_logs()->detach($this->divingLogId);
        $this->loadCurrentBuddies();
    }

    private function loadCurrentBuddies()
    {
        $this->currentBuddies = DivingBuddy::whereHas('diving_logs', function ($query) {
            $query->where('diving_log_id', $this->divingLogId);
        })->get();
    }

    public function closeModal()
    {
        $this->emit('close-modal');
    }

    public function render()
    {
        return view('livewire.add-buddies-component');
    }
}
