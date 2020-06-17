@component('mail::message')

# Felicitaciones
Fuiste acreedor a un nuevo cupón de descuento de:
+ **Producto:** [{{ $msg['producto'] }}](https://sazez.net/producto/1).
+ **Precio normal:** {{ $msg['precio_normal'] }} Bs.
+ **Precio descuento:** {{ $msg['precio_descuento'] }} Bs.
+ **Tienda:** {{ $msg['tienda'] }}.

@component('mail::promotion')

![Qr code][qr]

[qr]: {{ asset('qrs/' .$codigo. '.png') }}

<br>
Cupón valido hasta {{ $msg['fecha_final'] }}.

@endcomponent

Al momento de tu compra, muestra este código y se realizara el descuento<br>
Visitanos y conoce nuestros ofertas.

@endcomponent