<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderShippedNotification extends Notification
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
            ->subject('Pesanan '.$this->order->order_number.' telah dikirim')
            ->line('Kabariticus! Pesanan '.$this->order->order_number.' sedang dalam perjalanan.')
            ->action('Lacak Pesanan', route('pesanan.show', $this->order));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_shipped',
            'title' => 'Pesanan dikirim',
            'message' => 'Pesanan '.$this->order->order_number.' telah dikirim.',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'url' => route('pesanan.show', $this->order),
        ];
    }
}