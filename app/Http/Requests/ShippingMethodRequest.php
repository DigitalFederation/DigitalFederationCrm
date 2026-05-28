<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingMethodRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The shipping method name is required.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'shipping method name',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
