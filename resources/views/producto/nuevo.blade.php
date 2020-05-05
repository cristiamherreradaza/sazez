@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}">
@endsection

@section('content')

<div id="divmsg" style="display:none" class="alert alert-primary" role="alert"></div>
<div class="row">
    <!-- Column -->
    <div class="col-md-12">
        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="mb-0 text-white">PRODUCTO NUEVO</h4>
                    </div>
                    <form action="/Producto/guarda" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            <div class="vtabs ">
                                <ul class="nav nav-tabs tabs-vertical" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#principal" role="tab"><span><i class="ti-agenda"></i> PRINCIPAL</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#precios" role="tab"><span><i class="ti-money"></i> PRECIOS</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#detalles" role="tab"><span><i class="ti-ruler-pencil"></i> DETALLES</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#multimedia" role="tab"><span><i class="ti-image"></i> MULTIMEDIA</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="principal" role="tabpanel">
                                        <div class="p-3">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>CODIGO </label>
                                                        <input type="text" class="form-control" name="codigo" id="codigo">
                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>NOMBRE </label>
                                                        <input type="text" class="form-control" name="nombre" id="nombre">
                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>CATEGORIA </label>
                                                        <select name="categoria_id" class="form-control">
                                                            @foreach ($categorias as $c)
                                                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>TIPO </label>
                                                        <input type="text" class="form-control" name="tipo" id="tipo">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>MARCA </label>
                                                        <select name="marca_id" class="form-control">
                                                            @foreach ($marcas as $m)
                                                            <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                        
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>CANTIDAD </label>
                                                        <input type="number" class="form-control" name="cantidad" id="cantidad" min="0">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>P/COMPRA </label>
                                                        <input type="number" class="form-control" name="precio_compra" id="precio_compra" min="0" step="any">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>ALMACEN </label>
                                                        <select name="almacene_id" class="form-control">
                                                            @foreach ($almacenes as $a)
                                                            <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>                                                
                                            </div>

                                            
                                        </div>
                                    </div>

                                    <div class="tab-pane p-3" id="precios" role="tabpanel">
                                        <div class="row">
                                           <div class="table-responsive">
                                                <table class="table no-wrap">
                                                    <tbody>
                                                     @foreach ($escalas as $key => $e)
                                                     <tr>
                                                         <td>{{ $e->nombre }}</td>
                                                         <td>
                                                             <input type="number" class="form-control" name="precio_venta[]" id="precio_venta" min="0" step="any">
                                                             <input type="hidden" class="form-control" name="escalas[]" id="escala" value="{{ $e->id }}">
                                                         </td>
                                                     </tr>
                                                     @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane  p-3" id="detalles" role="tabpanel">
                                        <div class="row">

                                            <div class="col">
                                                <div class="form-group">
                                                    <label>COLOR </label>
                                                    <input type="text" class="form-control" name="colores" id="color">
                                                </div>
                                            </div>

                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>LARGO </label>
                                                    <input type="text" class="form-control" name="largo" id="largo" min="0" step="any">
                                                </div>
                                            </div>
                                        
                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>ANCHO </label>
                                                    <input type="text" class="form-control" name="ancho" id="ancho" min="0" step="any">
                                                </div>
                                            </div>
                                        
                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>ALTO </label>
                                                    <input type="text" class="form-control" name="alto" id="alto" min="0" step="any">
                                                </div>
                                            </div>

                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>PESO </label>
                                                    <input type="text" class="form-control" name="peso" id="peso" min="0" step="any">
                                                </div>
                                            </div>
                                        
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 nopadding">
                                                <div class="form-group">
                                                    <label>CARACTERISTICAS </label>
                                                    <div id="education_fields"></div>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="caracteristica" name="caracteristica[]" value="" >

                                                        <div class="input-group-append">
                                                            <button class="btn btn-success" type="button" onclick="education_fields();"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 nopadding">
                                                <div class="form-group">
                                                    <label>DESCRIPCION </label>
                                                    <textarea class="form-control" rows="5" name="descripcion"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane p-3" id="multimedia" role="tabpanel">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>URL ARTICULO </label>
                                                    <input type="text" class="form-control" name="url_referencia" id="url_referencia">
                                                </div>
                                            </div>

                                            <div class="col-md-6 nopadding">
                                                <div class="form-group">
                                                    <label>URL VIDEO </label>
                                                    <input type="text" class="form-control" name="video" id="video">
                                                </div>
                                            </div>
                                        
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-6 col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">Imagen</h4>
                                                        <input type="file" id="input-file-now" class="dropify" name="foto" />
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success">Guardar</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-inverse">Cancelar</button>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- Row -->
    </div>
    <!-- Column -->
</div>
@stop
@section('js')
<script src="{{ asset('assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script>
var room = 1;

$(document).ready(function() {
    // Basic
    $('.dropify').dropify();
});       

function education_fields() {

room++;
var objTo = document.getElementById('education_fields')
var divtest = document.createElement("div");
divtest.setAttribute("class", "form-group removeclass" + room);
var rdiv = 'removeclass' + room;
divtest.innerHTML = '<div class="input-group">\
                                <input type="text" class="form-control" id="caracteristica" name="caracteristica[]" value="">\
                                <div class="input-group-append"> <button class="btn btn-danger" type="button" onclick="remove_education_fields(' + room + ');"> <i class="fa fa-minus"></i> </button></div>\
                            </div>\
                    <div class="clear"></div>';

    objTo.appendChild(divtest)
    }

    function remove_education_fields(rid) {
    $('.removeclass' + rid).remove();
    }
</script>

@endsection