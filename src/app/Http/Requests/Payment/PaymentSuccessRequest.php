<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentSuccessRequest extends FormRequest
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
            // 'orderId' => 'validation.id',
            'checkInDay' => 'required',
            'checkOutDay' => 'required',
        ];
    }
}
