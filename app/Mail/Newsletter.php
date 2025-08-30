<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Client;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $sujet;
    public $contenu;

    public function __construct(Client $client, $sujet, $contenu)
    {
        $this->client = $client;
        $this->sujet = $sujet;
        $this->contenu = $contenu;
    }

    public function build()
    {
        return $this->subject($this->sujet)
                    ->view('emails.newsletter');
    }
}
