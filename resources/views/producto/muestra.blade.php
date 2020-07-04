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
                        <h1 class="mt-3"><strong class="text-primary"><u>Descripción del Producto</u></strong></h1>                        
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
                        <a class="nav-link active text-primary" id="pills-setting-tab" data-toggle="pill" href="#stock" role="tab" aria-controls="pills-setting" aria-selected="true"><strong>STOCK EN TIENDAS</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  text-primary" id="pills-timeline-tab" data-toggle="pill" href="#general" role="tab" aria-controls="pills-timeline" aria-selected="false"><strong>INFORMACI&Oacute;N GENERAL</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" id="pills-profile-tab" data-toggle="pill" href="#especificacion" role="tab" aria-controls="pills-profile" aria-selected="false"><strong>ESPECIFICACIONES</strong></a>
                    </li>                    
                </ul>
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="stock" role="tabpanel" aria-labelledby="pills-setting-tab">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-primary text-white text-center">
                                        <tr>
                                            <th>Almacen</th>
                                            <th>Ingresos</th>
                                            <th>Salidas</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach($almacenes as $almacen)
                                            <tr>
                                                <td><strong class="text-primary">{{ $almacen->nombre }}</strong></td>
                                                @php                                                    
                                                    $ingreso = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) as total'))
                                                        ->where('producto_id', $producto->id)
                                                        ->where('almacene_id', $almacen->id)
                                                        ->first();
                                                    $ingreso=intval($ingreso->total);
                                                @endphp
                                                <td>{{ $ingreso }}</td>
                                                @php                                                    
                                                    $salida = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(salida) as total'))
                                                        ->where('producto_id', $producto->id)
                                                        ->where('almacene_id', $almacen->id)
                                                        ->first();
                                                    $salida=intval($salida->total);
                                                @endphp
                                                <td>{{ $salida }}</td>
                                                <td>{{ ($ingreso-$salida) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-xs-6 b-r"><strong class="text-danger">CODIGO</strong>
                                    <br>
                                    <p>{{ $producto->codigo }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong class="text-danger">NOMBRE</strong>
                                    <br>
                                    <p>{{ $producto->nombre }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong class="text-danger">NOMBRE DE VENTA</strong>
                                    <br>
                                    <p>{{ $producto->nombre_venta }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6"><strong class="text-danger">MODELO</strong>
                                    <br>
                                    <p>{{ $producto->modelo }}</p>
                                </div>
                            </div>
                            <hr>
                            <p class="mt-4">
                                {{ $producto->descripcion }}
                            </p>  
                            <hr>
                            <h3><strong class="text-danger">Enlace Referencia :</strong></h3>
                                <a href="{{ $producto->url_referencia }}" target="_blank">{{ $producto->url_referencia }}</a>
                            <hr>
                            <h3><strong class="text-danger">Enlace Video :</strong></h3>
                                <a href="{{ $producto->video }}" target="_blank">{{ $producto->video }}</a>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="especificacion" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive col-md-6">
                                    <h4><strong class="text-success">Detalles T&eacute;cnicos</strong></h4>
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
                                    <h4><strong class="text-info">Caracteristicas</strong></h4>
                                    <table class="table table-hover table-info">
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
                </div>
        </div>
    </div>
</div>

@stop
@section('js')

@endsection