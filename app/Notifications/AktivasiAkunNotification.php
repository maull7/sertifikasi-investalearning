<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AktivasiAkunNotification extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Untuk Akun anda di website Sertifikasi Investalearning Telah Diaktifkan')
            ->greeting('Halo ' . $notifiable->name)
            ->line('Akun Anda telah berhasil diaktivasi...')
            ->line('Silakan login dan mulai menggunakan layanan kami....')
            ->salutation('Terima kasih.');
    }
}
