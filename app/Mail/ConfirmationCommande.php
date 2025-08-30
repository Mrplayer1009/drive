<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CommandeClient;

class ConfirmationCommande extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;

    public function __construct(CommandeClient $commande)
    {
        $this->commande = $commande;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre commande #' . $this->commande->id . ' - Driv\'n Cook',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmation-commande',
            with: [
                'commande' => $this->commande,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
