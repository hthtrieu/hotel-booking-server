<?php

namespace App\Mail;

use App\Dtos\Reservation\ReservationResponseDTO;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    public ReservationResponseDTO $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        // dd($data['invoice']);
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Confirm Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.reservation-confirm-mail',
            with: [
                'hotel' => $this->data->hotelDTO,
                'user' => $this->data->userDTO,
                'room_types' => $this->data->roomTypesDTO,
                'invoice' => $this->data->invoiceDTO,
                'reservation' => $this->data->reservationDTO,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
