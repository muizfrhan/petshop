<?php

namespace App\Notifications;

use App\Models\ServiceBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(public ServiceBooking $booking) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking '.$this->booking->statusLabel())
            ->greeting('Halo '.$this->booking->customer_name.',')
            ->line('Booking untuk '.$this->booking->pet_name.' pada '.$this->booking->booking_date->format('d M Y').' pukul '.$this->booking->booking_time.' telah dikonfirmasi.')
            ->action('Lihat Booking', route('booking.show', $this->booking));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_'.$this->booking->status,
            'title' => 'Booking '.$this->booking->statusLabel(),
            'message' => $this->booking->service?->name.' untuk '.$this->booking->pet_name.' tanggal '.$this->booking->booking_date->format('d M Y').'.',
            'booking_id' => $this->booking->id,
            'url' => route('booking.show', $this->booking),
        ];
    }
}