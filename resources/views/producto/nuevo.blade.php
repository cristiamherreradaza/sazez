@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
    <link href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
    <style>
    
        /* these styles are for the demo, but are not required for the plugin */
        .zoom {
            display: inline-block;
            position: relative;
            cursor: zoom-in;
        }
    
        /* magnifying glass icon */
        .zoom:after {
            content: '';
            display: block;
            width: 33px;
            height: 33px;
            position: absolute;
            top: 0;
            right: 0;
            background: url(icon.png);
        }
    
        .zoom img {
            display: block;
        }
    
        .zoom img::selection {
            background-color: transparent;
        }
    
    </style>
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

                            <div class="vtabs">
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
                                                        <input type="text" class="form-control" name="codigo" id="codigo" required>
                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>NOMBRE </label>
                                                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>NOMBRE COMERCIAL</label>
                                                        <input type="text" class="form-control" name="nombre_venta" id="nombre_venta">
                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>TIPOS </label>
                                                        <select name="tipo_id" class="form-control">
                                                            @foreach ($tipos as $c)
                                                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>MODELO </label>
                                                        <input type="text" class="form-control" name="modelo" id="modelo">
                                                    </div>
                                                </div>
                                        
                                            </div>

                                            <div class="row">

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

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>CANTIDAD </label>
                                                        <input type="number" class="form-control" name="cantidad" id="cantidad" min="0" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>P/COMPRA </label>
                                                        <input type="number" class="form-control" name="precio_compra" id="precio_compra" min="0" step="any" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>ALMACEN </label>
                                                        <select name="almacene_id" class="form-control">
                                                            @foreach ($almacenes as $a)
                                                            <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>CATEGORIAS </label>
                                                        <input type="hidden" value="" id="categorias_valores" name="categorias_valores">
                                                        <select class="select2 mb-2 select2-multiple" name="categorias" id="categorias" style="width: 100%" multiple="multiple" data-placeholder="Choose" required>
                                                            @foreach ($categorias as $c)
                                                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            
                                        </div>
                                    </div>

                                    <div class="tab-pane p-6" id="precios" role="tabpanel">
                                        <div class="row">
                                            @foreach ($escalas as $key => $e)
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-6 text-right col-form-label">{{ $e->nombre }}</label>
                                                        <div class="col-md-6">
                                                            <input type="number" class="form-control" name="precio_venta[]" id="precio_venta" min="0" step="any" value="0">
                                                            <input type="hidden" class="form-control" name="escalas[]" id="escala" value="{{ $e->id }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
                                                    <input type="text" class="form-control" name="largo" id="largo" min="0" step="any" value="0">
                                                </div>
                                            </div>
                                        
                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>ANCHO </label>
                                                    <input type="text" class="form-control" name="ancho" id="ancho" min="0" step="any" value="0">
                                                </div>
                                            </div>
                                        
                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>ALTO </label>
                                                    <input type="text" class="form-control" name="alto" id="alto" min="0" step="any" value="0">
                                                </div>
                                            </div>

                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>PESO </label>
                                                    <input type="text" class="form-control" name="peso" id="peso" min="0" step="any" value="0">
                                                </div>
                                            </div>
                                        
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 nopadding">
                                                <div class="form-group">
                                                    <label>CARACTERISTICAS </label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="caracteristica" name="caracteristica[]" value="" >

                                                        <div class="input-group-append">
                                                            <button class="btn btn-success" type="button" onclick="education_fields();"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div id="education_fields"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 nopadding">
                                                <div class="form-group">
                                                    <label>DESCRIPCION </label>
                                                    <textarea class="form-control" id="mymce" rows="5" name="descripcion"></textarea>
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

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>URL VIDEO </label>
                                                    <input type="text" class="form-control" name="video" id="video">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">Imagen 1</h4>
                                                        <input type="file" id="input-file-now" class="dropify" name="fotos[]" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">Imagen 2</h4>
                                                        <input type="file" id="input-file-now" class="dropify" name="fotos[]" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">Imagen 3</h4>
                                                        <input type="file" id="input-file-now" class="dropify" name="fotos[]" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">Imagen 4</h4>
                                                        <input type="file" id="input-file-now" class="dropify" name="fotos[]" />
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
                                    <a href="{{ url('Pedido/listado') }}">
                                        <button type="button" class="btn waves-effect waves-light btn-block btn-inverse">Cancelar</button>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline-primary">
                    <div class="card-header" onclick="muestra_formulario_importacion()">
                        <h4 class="mb-0 text-white">IMPORTAR EXCEL PRODUCTOS</h4>
                    </div>
                    <div class="card-body" id="bloque_formulario_importacion" style="display: none;">
                        <form action="/Producto/importaExcel" id="formularioImportaExcel" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">ARCHIVO</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="excel" class="custom-file-input" id="inputGroupFile01" accept=".xlsx" required>
                                                <label class="custom-file-label" for="inputGroupFile01">Seleccione...</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" id="btnEnviaExcel" onclick="enviaExcel();"
                                        class="btn waves-effect waves-light btn-block btn-success">Importar archivo
                                        excel</button>
                                    <button class="btn btn-primary btn-block" type="button" id="btnTrabajandoExcel"
                                        disabled style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        &nbsp;&nbsp;Estamos trabajando, ten pasciencia ;-)
                                    </button>

                                </div>
                                <div class="col-md-3">
                                    <a href="{{ asset('excels/formato_productos_vacio.xlsx') }}" target="_blank" rel="noopener noreferrer">
                                        <button type="button" class="btn waves-effect waves-light btn-block btn-warning">Descargar formato excel</button>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <span style="background-color: #ea6274; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    Colocar NT en caso de no tener informacion
                                </div>
                                <div class="col-md-3">
                                    <span
                                        style="background-color: #67ff67; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    Solo introducir numeros
                                </div>
                                <div class="col-md-3">
                                    <span
                                        style="background-color: #8065a9; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    Colocar 0 si no tienen el dato
                                </div>
                                <div class="col-md-3">
                                    <span
                                        style="background-color: #62adea; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    No dejar celdas vacias ni cambiar el orden
                                </div>
                                <div class="col-md-12">
                                    {{-- <img src="{{ asset('assets/images/muestra_excel_productos.png') }}" class="img-thumbnail" alt=""> --}}
                                    <span class='zoom' id='ex1'>
                                        <img src='{{ asset('assets/images/muestra_excel_productos.png') }}' class="img-thumbnail" alt='Daisy on the Ohoopee' />
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
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
<script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('js/jquery.zoom.js') }}"></script>

<script>
var room = 1;

function enviaExcel(){
    $("#btnEnviaExcel").hide();
    $("#btnTrabajandoExcel").show();
}

$(document).ready(function() {

$('#ex1').zoom();
    
    $('.dropify').dropify();
    $("#categorias").select2();
    $("#categorias").change(function(){
        valores = $("#categorias").val();
        $("#categorias_valores").val(valores);
    });

    if ($("#mymce").length > 0) {
        tinymce.init({
        selector: "textarea#mymce",
        theme: "modern",
        height: 200,
        plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist  numlist outdent indent | l ink image | print preview media fullpage | forecolor backcolor emoticons",
        
        });
    }
});       

function muestra_formulario_importacion()
{
    $("#bloque_formulario_importacion").toggle('slow');
}

function education_fields() {

room++;
var objTo = document.getElementById('education_fields')
var divtest = document.createElement("div");
divtest.setAttribute("class", "form-group removeclass" + room);
var rdiv = 'removeclass' + room;
divtest.innerHTML = '<div class="clear"></div>\
                        <div class="input-group">\
                                <input type="text" class="form-control" id="caracteristica" name="caracteristica[]" value="">\
                                <div class="input-group-append"> <button class="btn btn-danger" type="button" onclick="remove_education_fields(' + room + ');"> <i class="fa fa-minus"></i> </button></div>\
                            </div>';

    objTo.appendChild(divtest)
    }

    function remove_education_fields(rid) {
    $('.removeclass' + rid).remove();
    }
</script>

@endsection