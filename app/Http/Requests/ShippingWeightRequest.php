<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingWeightRequest extends FormRequest
{
    public function rules()
    {
        return [
            'method_id' => 'required|numeric|exists:shipping_methods,id',
            'range' => 'nullable|string|max:255',
            'minimum_weight' => 'required|numeric|min:0', // Make sure it's at least 0
            'maximum_weight' => 'required|numeric|min:0', // Make sure it's at least 0
        ];
    }

    public function messages()
    {
        return [
            'range.required' => 'The shipping weight range is required.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'shipping weight range',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
