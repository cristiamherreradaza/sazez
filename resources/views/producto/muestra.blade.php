@extends('layouts.app')

@section('css')
@endsection

@section('content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-12 align-self-center">
            <h1 class="page-title">Detalles del Producto</h1>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mt-2">
                        <li class="breadcrumb-item"><a href="#">INICIO</a></li>
                        <li class="breadcrumb-item active" aria-current="page">CATEGORIAS</li>
                        <li class="breadcrumb-item active" aria-current="page">PRODUCTO</li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $producto->nombre}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-5">
    <div class="row justify-content-md-center">
        <div class="card col-lg-10 col-xlg-10 col-md-10">
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
                    <div class="px-5 py-5">
                        <h1><strong>{{ $producto->nombre }}</strong></h1>
                        @php
                            $precio = App\Precio::where('producto_id', $producto->id)
                                        ->where('escala_id', 1)
                                        ->first();
                        @endphp
                        <h2 class="text-danger"><strong>{{ $precio->precio }} Bs.</strong></h2>
                        <ul class="list">
                            <li>
                                <span>Categorias :</span>
                                @foreach ($producto->categorias as $categorias)
                                    {{ $categorias->categoria->nombre }}
                                @endforeach
                            </li>
                            <li><span>Marca</span> : {{ $producto->marca->nombre }}</li>
                        </ul>
                        <h2 class="mt-3"><strong>Descripción</strong></h2>
                        <p> {{ $producto->descripcion }} </p>

                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-lg-2 col-xlg-2 col-md-2">
            <div class="card text-white bg-white">
                <div class="card-body">
                    <div class="white-box text-center ">
                        <img src='data:image/png;base64, {{ base64_encode(QrCode::format("png")->color(34,82,162)->size(200)->generate("$producto->codigo")) }}' class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-md-center">
    <div class="col-lg-10 col-xlg-9 col-md-7">
            <div class="card border-danger">
                <!-- Tabs -->
                <ul class="nav nav-pills custom-pills justify-content-md-center bg-danger" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-white" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true"><strong>DESCRIPCI&Oacute;N</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" id="pills-profile-tab" data-toggle="pill" href="#last-month" role="tab" aria-controls="pills-profile" aria-selected="false"><strong>ESPECIFICACIONES</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" id="pills-setting-tab" data-toggle="pill" href="#previous-month" role="tab" aria-controls="pills-setting" aria-selected="false"><strong>STOCK EN TIENDAS</strong></a>
                    </li>
                </ul>
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="current-month" role="tabpanel" aria-labelledby="pills-timeline-tab">
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
                    <div class="tab-pane fade" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
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
</div>

@stop
@section('js')

@endsection