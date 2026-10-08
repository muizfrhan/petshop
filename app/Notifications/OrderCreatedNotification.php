<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
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
            ->subject('Pesanan '.$this->order->order_number.' berhasil dibuat')
            ->greeting('Halo '.$this->order->customer_name.',')
            ->line('Terima kasih sudah berbelanja di PETCARE HOUSE.')
            ->line('Nomor pesanan: '.$this->order->order_number)
            ->line('Total: Rp '.number_format($this->order->total, 0, ',', '.'))
            ->action('Lihat Pesanan', route('pesanan.show', $this->order));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_created',
            'title' => 'Pesanan berhasil dibuat',
            'message' => 'Pesanan '.$this->order->order_number.' menunggu pembayaran Anda.',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'url' => route('pesanan.show', $this->order),
        ];
    }
}