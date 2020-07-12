<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PromocionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Cupón de promoción en oferta!";
    public $codigo;
    public $fecha_final;
    public $tienda;
    public $promocion;
    public $productos_promocion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($codigo, $fecha_final, $tienda, $promocion, $productos_promocion)
    {
        $this->codigo = $codigo;
        $this->fecha_final = $fecha_final;
        $this->tienda = $tienda;
        $this->promocion = $promocion;
        $this->productos_promocion = $productos_promocion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.promocion');
    }
}
