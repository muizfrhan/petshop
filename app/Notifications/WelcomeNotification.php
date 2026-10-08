<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(private array $data = []) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Selamat datang di PETCARE HOUSE')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Akun Anda di PETCARE HOUSE sudah aktif.')
            ->action('Mulai Belanja', route('produk.index'));
    }
}