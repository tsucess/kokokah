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

        return (new MailMessage)
            ->subject('Email Verification Code - Kokokah LMS')
            ->logo('https://kokokah.com/images/Kokokah_Logo.png')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Your email verification code is:')
            ->line('')
            ->line('**' . $this->verificationCode->code . '**')
            ->line('')
            ->line('This code will expire in ' . $expiresIn . ' minutes.')
            ->line('If you did not request this code, please ignore this email.')
            ->action('Verify Email', url('/verifypassword?code=' . $this->verificationCode->code))
            ->line('Or enter the code manually in the verification page.')
            ->line('')
            ->line('Thank you for using Kokokah LMS!');
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

