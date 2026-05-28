<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DivingLogFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'individual_id' => 'required|string',
            'dive_type' => 'nullable|string',
            'category' => 'nullable|string',
            'buddy_id' => 'nullable|integer|exists:buddies,id',
            'diving_location_id' => 'nullable|integer|exists:dive_locations,id',
            'date_and_time' => 'required|date_format:Y-m-d H:i:s',
            'dive_site_score' => 'nullable|integer|min:1|max:10',
            'environment_entry' => 'nullable|string',
            'environment_water_type' => 'nullable|string',
            'environment_current' => 'nullable|string',
            'environment_surface' => 'nullable|string',
            'environment_water_temperature' => 'nullable|integer',
            'environment_water_temperature_unit' => 'nullable|string|in:C,F',
            'environment_air_temperature' => 'nullable|integer',
            'environment_air_temperature_unit' => 'nullable|string|in:C,F',
            'environment_water_visibility' => 'nullable|integer',
            'environment_water_visibility_unit' => 'nullable|string',
            'wildlife' => 'nullable|string',
            'notes' => 'nullable|string',
            'status_class' => 'required|string',
            'freedivingData' => 'nullable|array',
            'divingData' => 'nullable|array',
            'extendedRangeData' => 'nullable|array',
            'rebreatherSCRData' => 'nullable|array',
            'rebreatherCCRData' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'individual_id.required' => 'Individual ID is required.',
            'date_and_time.required' => 'Date and time is required.',
            'date_and_time.date_format' => 'Date and time must be in the format Y-m-d H:i:s.',
            'buddy_id.integer' => 'Buddy ID must be an integer.',
            'buddy_id.exists' => 'Buddy ID must exist in the buddies table.',
            'diving_location_id.integer' => 'Dive location ID must be an integer.',
            'diving_location_id.exists' => 'Dive location ID must exist in the dive locations table.',
            'dive_site_score.integer' => 'Dive site score must be an integer.',
            'dive_site_score.min' => 'Dive site score must be at least 1.',
            'dive_site_score.max' => 'Dive site score must be at most 10.',
            'environment_water_temperature_unit.in' => 'Water temperature unit must be either "C" or "F".',
            'environment_air_temperature_unit.in' => 'Air temperature unit must be either "C" or "F".',
            'status_class.required' => 'Status class is required.',
        ];
    }
}
