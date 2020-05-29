@extends('layouts.tienda')

@section('content')

<!-- SECCION COLLECCION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- shop -->
            <div class="col-md-4 col-xs-6">
                <div class="shop">
                    <div class="shop-img">
                        <img src="{{ asset('tienda/img/shop01.png') }}" alt="">
                    </div>
                    <div class="shop-body">
                        <h3>Collección<br>de Laptops</h3>
                        <a href="#" class="cta-btn">Compralo ahora <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- /shop -->

            <!-- shop -->
            <div class="col-md-4 col-xs-6">
                <div class="shop">
                    <div class="shop-img">
                        <img src="{{ asset('tienda/img/shop03.png') }}" alt="">
                    </div>
                    <div class="shop-body">
                        <h3>Collección<br>de Accesorios</h3>
                        <a href="#" class="cta-btn">Compralo ahora <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- /shop -->

            <!-- shop -->
            <div class="col-md-4 col-xs-6">
                <div class="shop">
                    <div class="shop-img">
                        <img src="{{ asset('tienda/img/shop02.png') }}" alt="">
                    </div>
                    <div class="shop-body">
                        <h3>Collección<br>de Camaras</h3>
                        <a href="#" class="cta-btn">Compralo ahora <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- /shop -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECCION COLLECCION -->

<!-- SECCION PRODUCTOS  -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->

        <div class="row">

            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Productos</h3>
                    <div class="section-nav">
                        <ul class="section-tab-nav tab-nav">
                            <li class="active"><a data-toggle="tab" href="#tab1">Laptops</a></li>
                            <li><a data-toggle="tab" href="#tab1">Smartphones</a></li>
                            <li><a data-toggle="tab" href="#tab1">Camaras</a></li>
                            <li><a data-toggle="tab" href="#tab1">Accesorios</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /section title -->

            @foreach($productos as $key => $producto)
            
                <div class="col-md-3 col-xs-6">
                    <div class="product">
                        <div class="product-img">
                            @foreach($producto->imagenes as $imagen)
                                <img src="{{ asset('imagenesProductos/'.$imagen->imagen) }}" alt="" class="img-fluid" style="height:250px; width:250px;">
                                @break
                            @endforeach
                            <!-- <img src="{{ asset('tienda/img/product02.png') }}" alt=""> -->
                            <div class="product-label">
                                <span class="sale">-30%</span>
                                <span class="new">NUEVO</span>
                            </div>
                        </div>
                        <div class="product-body">
                            <p class="product-category">{{ $producto->marca->nombre }}</p>
                            <h3 class="product-name"><a href="#">{{ $producto->nombre }}</a></h3>
                            <h4 class="product-price"> 
                                @foreach($producto->precio as $precio)
                                    $ {{ $precio->precio }}
                                    <del class="product-old-price">$ {{ $precio->precio + 50 }}</del>
                                @endforeach 
                            </h4>
                            <div class="product-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="product-btns">
                                <button type="button" class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">lista de deseos</span></button>
                                <button type="button" class="quick-view" onclick="ver_producto('{{ $producto->id }}')"><i class="fa fa-eye"></i><span class="tooltipp">vista rapida</span></button>
                            </div>
                        </div>
                        <div class="add-to-cart">
                            <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> agregar al carrito</button>
                        </div>
                    </div>
                    {{ ($key+1) }}
                    @if($key%4==0)
                        hola
                    @endif
                </div>
            @endforeach
        </div>

        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECCION NUEVOS PRODUCTOS -->

<!-- SECCION EN OFERTA  -->
<div id="hot-deal" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="hot-deal">
                    <ul class="hot-deal-countdown">
                        <li>
                            <div>
                                <h3>02</h3>
                                <span>Dias</span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <h3>10</h3>
                                <span>Horas</span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <h3>34</h3>
                                <span>Minutos</span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <h3>60</h3>
                                <span>Segundos</span>
                            </div>
                        </li>
                    </ul>
                    <h2 class="text-uppercase">en oferta esta semana</h2>
                    <p>Colección Hasta el 50% de Descuento</p>
                    <a class="primary-btn cta-btn" href="#">Compralo ahora</a>
                </div>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECCION EN OFERTA -->

<!-- NEWSLETTER -->
<div id="newsletter" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="newsletter">
                    <p>Se el primero en saber de nuestras <strong>OFERTAS</strong></p>
                    <form>
                        <input class="input" type="email" placeholder="Ingresa tu correo electrónico">
                        <button class="newsletter-btn"><i class="fa fa-envelope"></i> Suscribirse</button>
                    </form>
                    <ul class="newsletter-follow">
                        <li>
                            <a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /NEWSLETTER -->

@stop

@section('js')
<script>
function ver_producto(producto_id)
{
    //alert(producto_id);
    window.location.href = "{{ url('Tienda/ver') }}/" + producto_id;
}

</script>
@endsection

