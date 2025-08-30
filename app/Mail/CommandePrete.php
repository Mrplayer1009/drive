<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CommandeClient;

class CommandePrete extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    public $codeRecuperation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CommandeClient $commande, $codeRecuperation)
    {
        $this->commande = $commande;
        $this->codeRecuperation = $codeRecuperation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Votre commande est prête ! - Driv\'n Cook')
                    ->view('emails.commande-prete');
    }
}

