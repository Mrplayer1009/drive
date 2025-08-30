<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CommandeClient;

class CommandeSurPlace extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    public $articles;
    public $token;

    public function __construct(CommandeClient $commande, $articles, $token)
    {
        $this->commande = $commande;
        $this->articles = $articles;
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre commande sur place #' . $this->commande->id . ' - Driv\'n Cook',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.commande-sur-place',
            with: [
                'commande' => $this->commande,
                'articles' => $this->articles,
                'token' => $this->token,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
