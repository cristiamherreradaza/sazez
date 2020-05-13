@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<link href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
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
                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#principal"
                                            role="tab"><span><i class="ti-agenda"></i> PRINCIPAL</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#precios"
                                            role="tab"><span><i class="ti-money"></i> PRECIOS</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#detalles"
                                            role="tab"><span><i class="ti-ruler-pencil"></i> DETALLES</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#multimedia"
                                            role="tab"><span><i class="ti-image"></i> MULTIMEDIA</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="principal" role="tabpanel">
                                        <div class="p-3">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>CODIGO </label>
                                                        <input type="text" class="form-control" name="codigo"
                                                            id="codigo" value="{{ $producto->codigo }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>NOMBRE </label>
                                                        <input type="text" class="form-control" name="nombre"
                                                            id="nombre" value="{{ $producto->nombre }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>NOMBRE COMERCIAL</label>
                                                        <input type="text" class="form-control" name="nombre_venta"
                                                            id="nombre_venta" value="{{ $producto->nombre_venta }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>TIPOS </label>
                                                        <select name="tipo_id" class="form-control">
                                                            @foreach ($tipos as $c)
                                                            <option value="{{ $c->id }}" {{ ($producto->tipo_id == $c->id)?"selected":"" }}>{{ $c->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>MODELO </label>
                                                        <input type="text" class="form-control" name="modelo"
                                                            id="modelo" value="{{ $producto->modelo }}">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>MARCA </label>
                                                        <select name="marca_id" class="form-control">
                                                            @foreach ($marcas as $m)
                                                            <option value="{{ $m->id }}" {{ ($producto->marca_id == $m->id)?"selected":"" }}>{{ $m->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>P/COMPRA </label>
                                                        <input type="number" class="form-control" name="precio_compra"
                                                            id="precio_compra" min="0" step="any" value="{{ $producto->precio_compra }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>CATEGORIAS </label>
                                                        <input type="hidden" value="" id="categorias_valores"
                                                            name="categorias_valores">
                                                        <select class="select2 mb-2 select2-multiple" name="categorias"
                                                            id="categorias" style="width: 100%" multiple="multiple"
                                                            data-placeholder="Choose" required>
                                                            @foreach ($categorias as $c)
                                                                @foreach ($categorias_productos as $cp)
                                                                    <option value="{{ $c->id }}" {{ ($cp->categoria_id == $c->id)?"selected":"" }}>{{ $c->nombre }}</option>
                                                                @endforeach
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
                                                    <label
                                                        class="col-sm-6 text-right col-form-label">{{ $e->nombre }}</label>
                                                    <div class="col-md-6">
                                                        @foreach ($precios as $p)
                                                            <input type="number" class="form-control" name="precio_venta[]" id="precio_venta" min="0" step="any" value="{{ ($p->escala_id==$e->id)?$p->precio:0 }}">
                                                        @endforeach
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
                                                    <input type="text" class="form-control" name="colores" id="color" value="{{ $producto->colores }}">
                                                </div>
                                            </div>

                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>LARGO </label>
                                                    <input type="text" class="form-control" name="largo" id="largo" min="0" step="any" value="{{ $producto->largo }}">
                                                </div>
                                            </div>

                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>ANCHO </label>
                                                    <input type="text" class="form-control" name="ancho" id="ancho" min="0" step="any" value="{{ $producto->ancho }}">
                                                </div>
                                            </div>

                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>ALTO </label>
                                                    <input type="text" class="form-control" name="alto" id="alto" min="0" step="any" value="{{ $producto->alto }}">
                                                </div>
                                            </div>

                                            <div class="col nopadding">
                                                <div class="form-group">
                                                    <label>PESO </label>
                                                    <input type="text" class="form-control" name="peso" id="peso" min="0" step="any" value="{{ $producto->peso }}">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 nopadding">
                                                <div class="form-group">
                                                    <label>CARACTERISTICAS </label>
                                                    
                                                    @foreach ($caracteristicas_producto as $cp)
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="caracteristica" name="caracteristica[]" value="{{ $cp->descripcion }}">
                                                        
                                                            <div class="input-group-append">
                                                                <button class="btn btn-danger" type="button" onclick="education_fields();"><i class="fa fa-minus"></i></button>
                                                            </div>
                                                        </div>    
                                                    <br>
                                                    @endforeach
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="caracteristica" name="caracteristica[]" value="">
                                                    
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
                                                    <textarea class="form-control" id="mymce" rows="5" name="descripcion">{{ $producto->descripcion }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane p-3" id="multimedia" role="tabpanel">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>URL ARTICULO </label>
                                                    <input type="text" class="form-control" name="url_referencia"
                                                        id="url_referencia">
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
                                                        <input type="file" id="input-file-now" class="dropify"
                                                            name="foto" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit"
                                        class="btn waves-effect waves-light btn-block btn-success">Guardar</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button"
                                        class="btn waves-effect waves-light btn-block btn-inverse">Cancelar</button>
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
                        <form action="/Producto/importa_excel" method="post" enctype="multipart/form-data">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>SELECCIONE ARCHIVO EXCEL</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">ARCHIVO</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="inputGroupFile01">
                                                <label class="custom-file-label"
                                                    for="inputGroupFile01">Seleccione...</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit"
                                        class="btn waves-effect waves-light btn-block btn-success">Importar</button>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button"
                                        class="btn waves-effect waves-light btn-block btn-warning">Descargar
                                        Formato</button>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-primary">Ver
                                        Formato</button>
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

<script>
    var room = 1;

$(document).ready(function() {

    $('.dropify').dropify();
    $("#categorias").select2();
    $("#categorias").change(function(){
        valores = $("#categorias").val();
        $("#categorias_valores").val(valores);
    });

    // $('#mymce').html({{ $producto->largo }});
    // tinymce.activeEditor.setContent('<span>some</span> html');

    if ($("#mymce").length > 0) {
        tinymce.init({
        selector: "textarea#mymce",
        theme: "modern",
        height: 200,
        // setContent: "algo",
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