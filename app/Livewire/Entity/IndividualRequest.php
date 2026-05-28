<?php

namespace App\Livewire\Entity;

use Domain\Individuals\Actions\CreateIndividualEntityAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class IndividualRequest extends Component
{
    public $code_cmas;

    public $member_number;

    protected function rules(): array
    {
        return [
            'code_cmas' => 'nullable|required_without:member_number|exists:individual,code_cmas',
            'member_number' => 'nullable|required_without:code_cmas|exists:individual,member_number',
        ];
    }

    protected function messages(): array
    {
        return [
            'code_cmas.required_without' => __('validation.required', ['attribute' => __('main.personal_id')]),
            'code_cmas.exists' => __('entity.invalid_cmas_code'),
            'member_number.required_without' => __('validation.required', ['attribute' => __('entity.member_number')]),
            'member_number.exists' => __('entity.invalid_member_number'),
        ];
    }

    public function submit(CreateIndividualEntityAction $action): mixed
    {
        $this->validate();

        try {
            $doInvite = $action->execute($this->code_cmas, Auth::user()->getEntityId(), $this->member_number);

            // Reset fields
            $this->code_cmas = '';
            $this->member_number = '';

            if (empty($doInvite)) {
                // Redirect with error - closes modal, shows message in main view
                return redirect()->route('entity.individual.index')
                    ->with('error', __('entity.member_must_have_federation'));
            }

            // Redirect with success - closes modal, shows message in main view
            return redirect()->route('entity.individual.index')
                ->with('success', __('entity.invitation_sent_success'));
        } catch (\Exception $ex) {
            // For exceptions, stay in modal to show error
            session()->flash('error', __('entity.error_creating_record', ['error' => $ex->getMessage()]));
            \Log::error($ex->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.entity.individual-request');
    }
}
