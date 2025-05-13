<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $language;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $language = 'en')
    {
        $this->token = $token;
        $this->language = $language;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjects = [
            'pt' => 'Ciano - Recuperação de Senha',
            'es' => 'Ciano - Recuperación de Contraseña',
            'it' => 'Ciano - Recupero Password',
            'fr' => 'Ciano - Réinitialisation du mot de passe',
            'de' => 'Ciano - Passwort zurücksetzen',
            'en' => 'Ciano - Password Reset'
        ];

        return new Envelope(
            subject: $subjects[$this->language] ?? $subjects['en'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $views = [
            'pt' => 'components.emails.reset-password-pt',
            'es' => 'components.emails.reset-password-es',
            'it' => 'components.emails.reset-password-it',
            'fr' => 'components.emails.reset-password-fr',
            'de' => 'components.emails.reset-password-de',
            'en' => 'components.emails.reset-password'
        ];

        return new Content(
            view: $views[$this->language] ?? $views['en'],
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
