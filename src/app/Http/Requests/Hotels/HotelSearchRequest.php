<?php

namespace App\Http\Requests\Hotels;

use Illuminate\Foundation\Http\FormRequest;

class HotelSearchRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'province' => config('validation.string'),
            'adult' => config('validation.integer'),
            'rooms' => config('validation.integer'),
            'checkin' => config('validation.date'),
            'checkout' => config('validation.date')
        ];
    }
}
