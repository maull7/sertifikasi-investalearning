<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AktivasiAkunNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Untuk Akun anda di website Sertifikasi Investalearning Telah Diaktifkan')
            ->greeting('Halo '.$notifiable->name)
            ->line('Akun Anda telah berhasil diaktivasi...')
            ->line('Silakan login dan mulai menggunakan layanan kami....')
            ->salutation('Terima kasih.');
    }
}
