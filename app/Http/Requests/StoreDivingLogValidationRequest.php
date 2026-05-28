<?php

namespace App\Http\Requests;

use Domain\Entities\Models\Entity;
use Domain\Individuals\Models\Individual;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDivingLogValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'cmas_code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $individualExists = Individual::where('code_cmas', $value)->exists();
                    $entityExists = Entity::where('code_cmas', $value)->exists();

                    if (! $individualExists && ! $entityExists) {
                        $fail('The CMAS code is invalid.');
                    }
                },
            ],
        ];
    }
}
