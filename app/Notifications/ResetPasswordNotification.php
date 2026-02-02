<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class ResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     */
    public string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the reset URL (same as Laravel default).
     */
    protected function resetUrl(object $notifiable): string
    {
        $parameters = [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ];

        if (Config::get('auth.verification.expire', false)) {
            return URL::temporarySignedRoute(
                'password.reset',
                Carbon::now()->addMinutes(
                    Config::get('auth.passwords.' . Config::get('auth.defaults.passwords') . '.expire', 60)
                ),
                $parameters
            );
        }

        return url(route('password.reset', $parameters, false));
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Password Akun InvestaLearning')
            ->view('emails.reset-password', [
                'resetUrl'   => $resetUrl,
                'notifiable' => $notifiable,
            ]);
    }
}

