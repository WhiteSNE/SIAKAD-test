<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpNotification extends Notification
{
    protected $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Kode OTP Ubah Password')
            ->line('Anda meminta kode OTP untuk mengubah password akun Anda.')
            ->line('Kode OTP Anda adalah:')
            ->greeting($this->otp) // Menampilkan OTP dengan ukuran besar
            ->line('Kode ini berlaku selama 15 menit.')
            ->line('Jika Anda tidak merasa meminta ini, abaikan email ini.');
    }
}