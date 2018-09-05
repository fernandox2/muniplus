<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Assistance;

class EnvioAsistencia extends Mailable
{
    use Queueable, SerializesModels;

    public $assistance;

    public function __construct($asistencia)
    {
        $this->assistance = $asistencia;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('enviodeasistencia@gmail.com')
                    ->view('emails.asistencia');
    }
}
