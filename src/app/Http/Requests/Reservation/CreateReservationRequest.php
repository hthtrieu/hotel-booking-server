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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_id' => 'validation.id',
            'note' => ['string'], //optional
            'name' => 'validation.string',
            'email' => 'validation.email',
            'phoneNumber' => 'validation.phone_number',
            'paymentMethod' => ['string'], //!todo: check optional or not
            'totalPrice' => 'validation.numeric',
            'transDate' => 'string', //transaction date
            'tax' => 'validation.numeric',
            'vat' => 'validation.numeric', //total price after tax
            'checkInDay' => '', //checkin day
            'checkOutDay' => '', // checkout day
        ];
    }
}
