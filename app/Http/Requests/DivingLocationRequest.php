<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DivingLocationRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'country_id' => 'nullable|integer|exists:country,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'region' => 'nullable|string|max:255',
            'native_name' => 'nullable|string|max:255',
            'lat' => 'numeric|nullable|required_with:lng|between:-90,90',
            'lng' => 'numeric|nullable|required_with:lat|between:-180,180',
            'depth' => 'nullable|string|max:50',
            'water_type' => ['nullable', 'string', Rule::in(['Salt Water', 'Fresh Water', 'Brackish Water'])],
            'dive_type' => ['nullable', 'array'],
            'dive_type.*' => ['string', Rule::in(['Open Water', 'Inland Waters', 'Wall or Canyon', 'Grotto', 'Cave', 'Wreck', 'Pool'])],
            'level' => ['nullable', 'array'],
            'level.*' => ['string', Rule::in(['Beginner', 'Intermediate', 'Advanced', 'Technical'])],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'notes' => 'nullable|string',
        ];
    }
}
