<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\VerificationCode;

class VerificationCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $verificationCode;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(VerificationCode $verificationCode, $type = 'email')
    {
        $this->verificationCode = $verificationCode;
        $this->type = $type;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $expiresIn = $this->verificationCode->expires_at->diffInMinutes(now());
        $logoPath = public_path('images/kokokah_logoo.png');

        return (new MailMessage)
            ->subject('Email Verification Code - Kokokah LMS')
            ->view('emails.verification-code', [
                'user' => $notifiable,
                'code' => $this->verificationCode->code,
                'expiresIn' => $expiresIn
            ])
            ->attach($logoPath, [
                'as' => 'logo.png',
                'mime' => 'image/png'
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->verificationCode->code,
            'type' => $this->type,
            'expires_at' => $this->verificationCode->expires_at
        ];
    }
}

