<?php

namespace App\Mail;

use App\Models\ProgramEdition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DuedProgramEditionEvaluation extends Mailable
{
    use Queueable, SerializesModels;

    public $programEdition;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProgramEdition $programEdition)
    {
        $this->programEdition = $programEdition;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('program-edition.dued_evaluation_email');
    }
}
