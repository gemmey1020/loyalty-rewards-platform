<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $token;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->user->email} - Activation Email",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.activate',
            with: [
                'user' => $this->user,
                'activationLink' => url('/activate/'.$this->token),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
