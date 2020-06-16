@component('mail::message')
# Felicitaciones

Fuiste acreedor a un nuevo cupón de descuento del producto <a href="https://www.sazez.net">{{ $msg['producto'] }}</a> en nuestra cadena de tiendas.

@component('mail::promotion')

<img src="{!!$message->embedData($codigo, 'QrCode.png', 'image/png')!!}">
<br>
Cupón valido hasta {{ $msg['fecha_final'] }}.<br>
@endcomponent

<!-- @component('mail::button', ['url' => ''])
Visitanos
@endcomponent -->

Al momento de tu compra, muestra este código y se realizara el descuento<br>
Visitanos y conoce nuestros ofertas.
@endcomponent