<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SingUp extends Mailable
{
    use Queueable, SerializesModels;

    public string $username;
    public string $password;
    public string $first_name;


    /**
     * Create a new message instance.
     */
    public function __construct($first_name,$username,$password)
    {
        $this->first_name = $first_name;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sing Up',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $logoPath = public_path('qiam.jpg');
        $logo = base64_encode(file_get_contents($logoPath));

        echo $logoPath;

        return new Content(
            view: 'SingUpView',
            with: ['logo' => $logo]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
