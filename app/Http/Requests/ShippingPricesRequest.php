<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingPricesRequest extends FormRequest
{
    // Determine if the user is authorized to make this request
    public function authorize()
    {
        // For simplicity, we return true here.
        // You should implement your authorization logic
        return true;
    }

    // Get the validation rules that apply to the request
    public function rules()
    {
        return [
            'zone_id' => 'required|exists:shipping_zones,id',
            'weight_id' => 'required|exists:shipping_weights,id',
            'method_id' => 'required|exists:shipping_methods,id',
            'price' => 'required|numeric|min:0',
        ];
    }

    // Get custom messages for validator errors
    public function messages()
    {
        return [
            // Custom validation messages
            'zone_id.required' => 'The shipping zone field is required.',
            'weight_id.required' => 'The weight range field is required.',
            'method_id.required' => 'The shipping method field is required.',
            'price.required' => 'The price field is required.',
            // ... any other messages
        ];
    }
}
