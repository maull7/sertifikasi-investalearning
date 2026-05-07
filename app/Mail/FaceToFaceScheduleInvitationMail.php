<?php

namespace App\Mail;

use App\Models\FaceToFaceSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FaceToFaceScheduleInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly FaceToFaceSchedule $schedule,
        public readonly string $joinUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Jadwal Tatap Muka - ' . $this->schedule->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.face-to-face-invitation',
        );
    }
}
