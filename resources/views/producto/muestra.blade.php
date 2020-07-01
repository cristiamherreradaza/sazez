@extends('layouts.app')

@section('css')
@endsection

@section('content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-12 align-self-center">
            <h1 class="page-title">Detalles de {{ $producto->nombre }}</h1>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mt-2">
                        <li class="breadcrumb-item"><a href="{{ url('home') }}">INICIO</a></li>
                        <!-- <li class="breadcrumb-item active" aria-current="page">CATEGORIAS</li> -->
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('Producto/listado') }}">PRODUCTOS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $producto->nombre}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-5">
    <div class="row justify-content-md-center">
        <div class="card col-md-12">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if(count($producto->imagenes) != 0)
                        <!-- empiezo de carrusel -->
                        <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">                    
                                @for($i = 0; $i< count($producto->imagenes); $i++ )
                                    <li data-target="#carouselExampleIndicators2" data-slide-to="{{ $i }}" class="{{ $i==0 ? 'active' : '' }}"></li>
                                @endfor
                            </ol>
                            <div class="carousel-inner my-4" role="listbox">
                                @foreach($producto->imagenes as $key => $imagen)
                                    <div class="carousel-item{{ $key == 0 ? ' active' : '' }}">
                                        <img class="img-fluid align-center" src="{{ asset('imagenesProductos/'.$imagen->imagen) }}">
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <!-- fin de carrusel -->
                    @else
                        <img src="{{ asset('assets/images/product/nube.png') }}" class="img-fluid" style="height:400px; width:350px;">
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="row px-4 py-3">
                        <h2 class="mt-3"><strong class="text-primary">Descripción del Producto</strong></h2>                        
                    </div>
                    <div class="row px-4">
                        <p> {{ $producto->descripcion }} </p>
                    </div>
                    <div class="row px-2">
                        <div class="col-md-8">
                            <h1><strong class="text-info">{{ $producto->nombre }}</strong></h1>
                            @php
                                $precio = App\Precio::where('producto_id', $producto->id)
                                            ->where('escala_id', 1)
                                            ->first();
                            @endphp
                            <h2><strong><span class="text-danger">Precio: </span>{{ $precio->precio }} Bs.</strong></h2>
                            <h2>
                                <strong>
                                    <span class="text-danger">Categorias :</span>
                                    @foreach ($producto->categorias as $categorias)
                                        {{ $categorias->categoria->nombre }}
                                    @endforeach
                                </strong>
                            </h2>
                            <h2><strong><span class="text-danger">Marca: </span>{{ $producto->marca->nombre }}</strong></h2>
                            <h2><strong><span class="text-danger">Tipo: </span>{{ $producto->tipo->nombre }}</strong></h2>
                        </div>
                        <div class="col-md-4  text-center">
                            <img src='data:image/png;base64, {{ base64_encode(QrCode::format("png")->color(116,96,238)->size(200)->generate("$producto->codigo")) }}' class="img-responsive">
                        </div>
                    </div>
                </div>
            </div>            
        </div>        
    </div>
    <div class="row justify-content-md-center">
        <div class="card col-md-12">           
                <!-- Tabs -->
                <ul class="nav nav-pills custom-pills justify-content-md-center" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-primary" id="pills-setting-tab" data-toggle="pill" href="#previous-month" role="tab" aria-controls="pills-setting" aria-selected="true"><strong>STOCK EN TIENDAS</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  text-primary" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="false"><strong>INFORMACI&Oacute;N GENERAL</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" id="pills-profile-tab" data-toggle="pill" href="#last-month" role="tab" aria-controls="pills-profile" aria-selected="false"><strong>ESPECIFICACIONES</strong></a>
                    </li>                    
                </ul>
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade" id="current-month" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-xs-6 b-r text-dark"> <strong>CODIGO</strong>
                                    <br>
                                    <p>{{ $producto->codigo }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r text-dark"> <strong>NOMBRE</strong>
                                    <br>
                                    <p>{{ $producto->nombre }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r text-dark"> <strong>NOMBRE DE VENTA</strong>
                                    <br>
                                    <p>{{ $producto->nombre_venta }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 text-dark"> <strong>MODELO</strong>
                                    <br>
                                    <p>{{ $producto->modelo }}</p>
                                </div>
                            </div>
                            <hr>
                            <p class="mt-4">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries </p>
                            <p>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>                            
                        </div>
                    </div>
                    <div class="tab-pane fade" id="last-month" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive col-md-6">
                                    <h4>Detalles T&eacute;cnicos</h4>
                                    <table class="table table-hover table-success">
                                        <tbody>
                                            <tr>
                                                <th>Largo</th>
                                                <td>{{ $producto->largo }}</td>
                                            </tr>
                                            <tr>
                                                <th>Ancho</th>
                                                <td>{{ $producto->ancho }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alto</th>
                                                <td>{{ $producto->alto }}</td>
                                            </tr>
                                            <tr>
                                                <th>Peso</th>
                                                <td>{{ $producto->peso }}</td>
                                            </tr>
                                            <tr>
                                                <th>Color</th>
                                                <td>{{ $producto->colores }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive col-md-6">
                                    <h4>Caracteristicas</h4>
                                    <table class="table table-hover table-danger">
                                        <tbody>
                                            @foreach($producto->caracteristica as $key => $caracteristicas)
                                                <tr>
                                                    <th>
                                                        {{ ($key+1) }}
                                                    </th>
                                                    <td>
                                                        {{ $caracteristicas->descripcion }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show active" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                        <div class="card-body">
                            <form class="form-horizontal">
                                <div class="form-body">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label class="control-label text-center text-info col-md-12"><strong>ALMACEN</strong></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label class="control-label text-center text-info col-md-12"><strong>INGRESOS</strong></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label class="control-label text-center text-info col-md-12"><strong>SALIDAS</strong></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label class="control-label text-center text-info col-md-12"><strong>STOCK</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach($almacenes as $almacen)
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group row">
                                                        <label class="control-label text-center col-md-12">{{ $almacen->nombre }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group row">
                                                        <label class="control-label text-center col-md-12">
                                                            @php
                                                                $ingreso = rand(50, 100)
                                                            @endphp
                                                            {{ $ingreso }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group row">
                                                        <label class="control-label text-center col-md-12">
                                                            @php
                                                                $salida = rand(1, 40)
                                                            @endphp
                                                            {{ $salida }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group row">
                                                        <label class="control-label text-center col-md-12">{{ ($ingreso-$salida) }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

@stop
@section('js')

@endsection