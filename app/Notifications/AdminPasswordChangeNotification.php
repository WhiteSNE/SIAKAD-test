<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPasswordChangeNotification extends Notification
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Peringatan: Perubahan Password User')
            ->greeting('Halo Admin!')
            ->line('Pemberitahuan bahwa user berikut telah mengubah password mereka:')
            ->line('Nama: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->line('Waktu: ' . now()->toDayDateTimeString())
            ->action('Cek Log Aktifitas', url('/admin/activity-logs'))
            ->line('Harap pantau jika terjadi aktifitas yang mencurigakan.');
    }
}