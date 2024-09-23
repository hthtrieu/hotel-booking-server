<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class CreateReservationRequest extends FormRequest
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
            'hotel_id' => config('validation.id'),
            'note' => ['string'], //optional
            'name' => config('validation.string'),
            'email' => config('validation.email'),
            'phoneNumber' => config('validation.phone_number'),
            'paymentMethod' => ['string'], //!todo: check optional or not
            'totalPrice' => config('validation.numeric'),
            'transDate' => 'string', //transaction date
            'tax' => config('validation.numeric'),
            'vat' => config('validation.numeric'), //total price after tax
            'checkInDay' => config('validation.string'), //checkin day
            'checkOutDay' => config('validation.string'), // checkout day
            'roomTypeReservedList' => ['array'],
        ];
    }
}
