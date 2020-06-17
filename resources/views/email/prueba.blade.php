@component('mail::message')
# Felicitaciones
Fuiste acreedor a un nuevo cupón de descuento de:
+ **Producto:** [Silla Gamer](https://sazez.net/producto/1).
+ **Precio normal:** 100 Bs.
+ **Precio descuento:** 80 Bs.
+ **Tienda:** El Prado esq Colon # 123.

@component('mail::promotion')

![Qr code][qr]

[qr]: {{ asset('qrs/' .$png. '.png') }}

Cupón valido hasta 2020-10-01.
@endcomponent

Al momento de tu compra, muestra este código y se realizara el descuento<br>
Visitanos y conoce nuestros ofertas.
@endcomponent