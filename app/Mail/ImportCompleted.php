<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImportCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $collectionsCount;

    public function __construct($collectionsCount)
    {
        $this->collectionsCount = $collectionsCount;
    }

    public function build()
    {
        return $this->subject('Importation terminée')
                    ->view('emails.import-completed')
                    ->with(['collectionsCount' => $this->collectionsCount]);
    }
}
