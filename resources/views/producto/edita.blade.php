@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
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
        <form action="{{ url('Producto/guarda') }}" method="post" enctype="multipart/form-data" >
            @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-info">
                    <div class="card-header bg-info">
                        <h4 class="mb-0 text-white">EDICION PRODUCTO</h4>
                    </div>
                        <div class="card-body">

                            <div class="row" id="tabsProductos">
                                <div class="col-md-3">
                                    <button type="button" id="tab1" class="btn btn-block btn-danger activo">PRINCIPAL</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" id="tab2" class="btn btn-block btn-primary inactivo">PRECIOS</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" id="tab3" class="btn btn-block btn-warning inactivo">CARACTERISTICAS</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" id="tab4" class="btn btn-block btn-info inactivo">MULTIMEDIA</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 tabContenido" id="tab1C">
                                    <div class="card border-danger">
                                        <div class="card-body">
                                            <div class="form-row">
                                                @php
                                                    $configuracion = App\Configuracione::where('descripcion', 'generacionCodigos')->first();
                                                @endphp

                                                @if ($configuracion->valor == 'No')
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="nombre">
                                                                CODIGO
                                                                <span class="text-danger">
                                                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                                                </span>
                                                            </label>
                                                            <input type="text" class="form-control" name="codigo" id="validationTooltip01" value="{{ $producto->codigo }}"utofocus required>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="nombre">
                                                            NOMBRE
                                                            <span class="text-danger">
                                                                <i class="mr-2 mdi mdi-alert-circle"></i>
                                                            </span>
                                                        </label>
                                                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                                        <input type="text" class="form-control" name="nombre" id="validationTooltip01"
                                                            value="{{ $producto->nombre }}" autofocus required>
                                                    </div>
                                                </div>
                                            
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>NOMBRE COMERCIAL</label>
                                                        <input type="text" class="form-control" name="nombre_venta" id="nombre_venta"
                                                            value="{{ $producto->nombre_venta }}">
                                                    </div>
                                                </div>
                                            
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>TIPOS </label>
                                                        <select name="tipo_id" class="form-control">
                                                            @foreach ($tipos as $c)
                                                            <option value="{{ $c->id }}" {{ ($producto->tipo_id == $c->id)?"selected":"" }}>{{ $c->nombre }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>MODELO </label>
                                                        <input type="text" class="form-control" name="modelo" id="modelo" value="{{ $producto->modelo }}">
                                                    </div>
                                                </div>
                                            
                                            </div>
                                            
                                            <div class="row">
                                            
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>MARCA </label>
                                                        <select name="marca_id" class="form-control">
                                                            @foreach ($marcas as $m)
                                                            <option value="{{ $m->id }}" {{ ($producto->marca_id == $m->id)?"selected":"" }}>{{ $m->nombre }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>P/COMPRA
                                                            <span class="text-danger">
                                                                <i class="mr-2 mdi mdi-alert-circle"></i>
                                                            </span>
                                                        </label>
                                                        <input type="number" class="form-control" name="precio_compra" id="precio_compra" min="0" step="any"
                                                            required value="{{ $producto->precio_compra }}">
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>
                                                            CANT/MIN
                                                        </label>
                                                        <input type="number" class="form-control" name="cantidad_minima" id="cantidad_minima" min="1" value="{{ $producto->cantidad_minima }}"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>DIAS DE GARANTIA
                                                            <span class="text-danger">
                                                                <i class="mr-2 mdi mdi-alert-circle"></i>
                                                            </span>
                                                        </label>
                                                        <input type="number" class="form-control" name="dias_garantia" id="dias_garantia" min="0" step="any"
                                                            required value="{{ $producto->dias_garantia }}">
                                                    </div>
                                                </div>
                                            
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>CATEGORIAS
                                                            <span class="text-danger">
                                                                <i class="mr-2 mdi mdi-alert-circle"></i>
                                                            </span>
                                                        </label>
                                                        <input type="hidden" value="" id="categorias_valores" name="categorias_valores">
                                                        <select class="select2 mb-2 select2-multiple" name="categorias" id="categorias" style="width: 100%"
                                                            multiple="multiple" data-placeholder="Choose" required>
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
                                </div>

                                <div class="col-md-12 tabContenido" id="tab2C" style="display: none;">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach ($escalas as $key => $e)
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                            <label>{{ $e->nombre }}</label>
                                                            @php
                                                            $preciosProducto = App\Precio::where('escala_id', $e->id)->where('producto_id', $producto->id)->first();
                                                            if($preciosProducto){
                                                            $precio = $preciosProducto->precio;
                                                            }else{
                                                            $precio = 0;
                                                            }
                                                            @endphp
                                                            <input type="number" class="form-control" name="precio_venta[{{ $e->id }}]" id="precio_venta" min="0"
                                                                step="any" value="{{ $precio }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Cantidad</label>
                                                            <input type="text" class="form-control" name="minimo" value="{{ $e->minimo }}" readonly>
                                                        </div>
                                                        {{-- <div class="col-md-4">
                                                                                                        <label>Maximo</label>
                                                                                                        <input type="text" class="form-control" name="maximo" value="{{ $e->maximo }}"
                                                        readonly>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 tabContenido" id="tab3C" style="display: none;">
                                    <div class="card border-warning">
                                        <div class="card-body">
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
                                                        <input type="text" class="form-control" name="largo" id="largo" min="0" step="any"
                                                            value="{{ $producto->largo }}">
                                                    </div>
                                                </div>
                                            
                                                <div class="col nopadding">
                                                    <div class="form-group">
                                                        <label>ANCHO </label>
                                                        <input type="text" class="form-control" name="ancho" id="ancho" min="0" step="any"
                                                            value="{{ $producto->ancho }}">
                                                    </div>
                                                </div>
                                            
                                                <div class="col nopadding">
                                                    <div class="form-group">
                                                        <label>ALTO </label>
                                                        <input type="text" class="form-control" name="alto" id="alto" min="0" step="any"
                                                            value="{{ $producto->alto }}">
                                                    </div>
                                                </div>
                                            
                                                <div class="col nopadding">
                                                    <div class="form-group">
                                                        <label>PESO </label>
                                                        <input type="text" class="form-control" name="peso" id="peso" min="0" step="any"
                                                            value="{{ $producto->peso }}">
                                                    </div>
                                                </div>
                                            
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4 nopadding">
                                                    <div class="form-group">
                                                        <label>CARACTERISTICAS </label>
                                                        @foreach ($caracteristicas_producto as $cp)
                                                        <div class="input-group removeclass{{ $cp->id }}">
                                                            <input type="text" class="form-control" id="caracteristica" name="caracteristica[]"
                                                                value="{{ $cp->descripcion }}">
                                            
                                                            <div class="input-group-append">
                                                                <button class="btn btn-danger" type="button" onclick="remove_education_fields({{ $cp->id }});"><i
                                                                        class="fa fa-minus"></i></button>
                                                            </div>
                                            
                                                        </div>
                                                        <br />
                                                        @endforeach
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="caracteristica" name="caracteristica[]" value="">
                                            
                                                            <div class="input-group-append">
                                                                <button class="btn btn-success" type="button" onclick="education_fields();"><i
                                                                        class="fa fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div id="education_fields"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 nopadding">
                                                    <div class="form-group">
                                                        <label>DESCRIPCION </label>
                                                        <textarea class="form-control" id="mymce" rows="5"
                                                            name="descripcion">{{ $producto->descripcion }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 tabContenido" id="tab4C" style="display: none;">
                                    <div class="card border-info">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>URL ARTICULO </label>
                                                        <input type="text" class="form-control" name="url_referencia" id="url_referencia"
                                                            value="{{ $producto->url_referencia }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>URL VIDEO </label>
                                                        <input type="text" class="form-control" name="video" id="video" value="{{ $producto->video }}">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row" id="bloqueAjaxListadoImg">

                                                @foreach ($imagenes_producto as $ip)

                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4 class="card-title">Imagen</h4>
                                                            <img src="{{ asset('imagenesProductos')."/".$ip->imagen }}" alt="" height="200">
                                                            <br />
                                                            <button type="button" class="btn waves-effect waves-light btn-danger"
                                                                onclick="elimina_imagen({{ $ip->id }}, {{ $ip->producto_id }})"> <i class="fas fa-trash-alt"></i>
                                                                Eliminar</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                @endforeach

                                            </div>

                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Imagen 1</span>
                                                        </div>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="fotos[]" id="inputGroupFile01">
                                                            <label class="custom-file-label" for="inputGroupFile01">Seleccione</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Imagen 2</span>
                                                        </div>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="fotos[]" id="inputGroupFile01">
                                                            <label class="custom-file-label" for="inputGroupFile01">Seleccione</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Imagen 3</span>
                                                        </div>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="fotos[]" id="inputGroupFile01">
                                                            <label class="custom-file-label" for="inputGroupFile01">Seleccione</label>
                                                        </div>
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
                                    <a href="{{ url('Producto/listado') }}">
                                        <button type="button" class="btn waves-effect waves-light btn-block btn-inverse">Cancelar</button>
                                    </a>
                                </div>
                            </div>

                        </div>
                    

                </div>
            </div>
        </div>
        </form>

        <!-- Row -->
    </div>
    <!-- Column -->
</div>
@stop
@section('js')
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/forms/select2/select2.init.js') }}"></script>
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>

<script src="{{ asset('js/jquery.zoom.js') }}"></script>

<script>
var room = 1;

(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// generamos los tabs
$('#tabsProductos div .btn').click(function () {
    var t = $(this).attr('id');

    if ($(this).hasClass('inactivo')) { //preguntamos si tiene la clase inactivo 
        $('#tabsProductos div .btn').addClass('inactivo');
        $(this).removeClass('inactivo');

        $('.tabContenido').hide();
        $('#' + t + 'C').fadeIn('slow');
    }
});

$(document).ready(function() {

    $("#categorias_valores").val($("#categorias").val());
    $("#categorias").select2();
    $("#categorias").change(function(){
        valores = $("#categorias").val();
        $("#categorias_valores").val(valores);
    });

    if ($("#mymce").length > 0) {
        tinymce.init({
            selector: "textarea#mymce",
            theme: "modern",
            height: 150,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
        });
    }

});       

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

function elimina_imagen(imagen_id, producto_id)
{
    Swal.fire({
        title: 'Quieres borrar la imagen?',
        text: "Luego no podras recuperarlo!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, estoy seguro!',
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {

            $.ajax({
                type:'GET',
                url:"{{ url('Producto/elimina_imagen') }}/"+imagen_id+"/"+producto_id,
                success:function(data){
                    $("#bloqueAjaxListadoImg").load('{{ url('Producto/ajaxMuestraImgProducto') }}/'+producto_id);        
                    Swal.fire(
                        'Excelente!',
                        'La imagen fue eliminada',
                        'success'
                        );
                }
            });
        }
    })
}
</script>

@endsection