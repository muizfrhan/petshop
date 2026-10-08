<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembayaran '.$this->order->order_number.' diterima')
            ->line('Pembayaran untuk pesanan '.$this->order->order_number.' telah kami terima.')
            ->line('Pesanan Anda akan segera kami proses.')
            ->action('Lihat Pesanan', route('pesanan.show', $this->order));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_confirmed',
            'title' => 'Pembayaran diterima',
            'message' => 'Pembayaran pesanan '.$this->order->order_number.' telah dikonfirmasi.',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'url' => route('pesanan.show', $this->order),
        ];
    }
}