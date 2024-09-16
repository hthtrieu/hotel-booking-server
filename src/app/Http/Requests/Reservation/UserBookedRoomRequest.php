<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class UserBookedRoomRequest extends FormRequest
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
            'hotel_id' => 'validation.id',
            'note' => ['string'], //optional
            'name' => 'validation.string',
            'email' => 'validation.email',
            'phoneNumber' => 'validation.phone_number',
            'paymentMethod' => ['string'], //!todo: check optional or not
            'totalPrice' => 'validation.numeric',
            'orderId' => 'validation.id',
            ''
            //          private String note;
            // private String name;
            // @Email(message = "invalid email address")
            // private String email;
            // private String phoneNumber;
            // private String paymentMethod;
            // private List<RoomTypeReserved> roomTypeReservedList;
            // private Double totalPrice;
            // private String orderId;
            // private String transDate;
            // private Double tax;
            // private Double vat;
            // private LocalDate startDay;
            // private LocalDate endDay;
        ];
    }
}
