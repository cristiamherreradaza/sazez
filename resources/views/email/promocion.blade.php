@component('mail::message')

# Felicitaciones
Fuiste acreedor a {{ $promocion->nombre }} que contiene:
@foreach($productos_promocion as $producto)
+ **Producto:** {{ $producto->producto->nombre }}, {{ $producto->cantidad }} unidad(es) a {{ round($producto->precio) }} Bs.
@endforeach
<br>
Puedes efectuar esta promocion en:
<br>
{{ $tienda }}

@component('mail::promotion')

![Qr code][qr]

[qr]: {{ asset('qrs/' .$codigo. '.png') }}

<br>
Codigo {{ $codigo }}
<br>
Cupón valido hasta {{ $fecha_final }}.

@endcomponent

Al momento de tu compra, muestra este código y se realizara el descuento<br>
Visitanos y conoce nuestros ofertas.

@endcomponent