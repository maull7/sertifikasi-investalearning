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
        $loginUrl = url('/login');

        return (new MailMessage)
            ->subject('Akun InvestaLearning Anda Telah Diaktifkan')
            ->view('emails.aktivasi-akun', [
                'user' => $notifiable,
                'loginUrl' => $loginUrl,
                'appUrl' => config('app.url'),
                'logoUrl' => config('app.logo_url'),
            ]);
    }
}
