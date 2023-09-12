<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ichtrojan\Otp\Otp;
use JetBrains\PhpStorm\Pure;


class EmailVerificationNotification extends Notification
{
    use Queueable;

    public string $message;
    public string $subject;
    public string $fromEmail;
    public string $mailer;
    private Otp $opt;

    /**
     * Create a new notification instance.
     */
    #[Pure] public function __construct()
    {
        $this->message = 'فيما يلي رمز التحقق الخاص بك';
        $this->subject = 'رمز التحقق';
        $this->fromEmail = 'stomeh6@gmail.com';
        $this->mailer = 'smtp';
        $this->otp = new Otp();
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
        $otp = $this->otp->generate($notifiable->email, 6, 60);
        return (new MailMessage)
            ->mailer('smtp')
            ->subject($this->subject)
            ->greeting('مرحباً ' . $notifiable->first_name)
            ->line($this->message)
            ->line('رمز التحقق: ' . $otp->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
