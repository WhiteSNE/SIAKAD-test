<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Keamanan Akun: Password Berhasil Diubah')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Kami memberitahukan bahwa password akun Anda baru saja berhasil diubah.')
            ->line('Jika ini bukan Anda, segera hubungi admin atau lakukan reset password.')
            ->action('Ke Dashboard', url('/dashboard'))
            ->line('Terima kasih telah menggunakan layanan kami!');
    }
}