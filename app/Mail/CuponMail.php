<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CuponMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Cupón de Oferta!";

    public $msg;
    public $codigo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msg, $codigo)
    {
        $this->msg = $msg;
        $this->codigo = $codigo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.cupon');
    }
}
