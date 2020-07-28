@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')


<div class="card card-outline-info">
    <form action="{{ url('Producto/guardaGarantia') }}"  method="POST">
        @csrf
        @if (Session('success'))
        <div class="alert alert-success alert-rounded"> <h3 class="text-success"><i class="fa fa-check-circle"></i> {{ Session('success') }} 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button></h3>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline-info">                                
                    <div class="card-header bg-info">
                        <h4 class="mb-0 text-white">GARANTIAS</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Tipos
                                        <span class="text-danger">
                                            <i class="mr-2 mdi mdi-alert-circle"></i>
                                        </span>
                                    </label>
                                    <select name="tipo_id" id="tipo_id" class="form-control" autofocus required>
                                        <option value=""> Seleccionar </option>
                                        @foreach($tipos as $tipo)
                                            <option value="{{ $tipo->id }}"> {{ $tipo->nombre }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Dias de garantia
                                        <span class="text-danger">
                                            <i class="mr-2 mdi mdi-alert-circle"></i>
                                        </span>
                                    </label>
                                    <input type="number" name="dias" id="dias" class="form-control" size="10" min="1" max="100000" pattern="^[0-9]+" step="any" autofocus required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="listadoProductosAjax"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" id="listaProductosAjax">
                
            </div>
        </div>
    </form>
</div>

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>
    // Funcion para la obtencion de los productos mediante el select tipo
    $('#tipo_id').on('change', function(e){
            var tipo = e.target.value;
            $.ajax({
            type:'GET',
            url:"{{ url('Producto/ajaxProductosGarantia') }}",
            data: {
                tipo : tipo
            },
            success:function(data){
                $("#listaProductosAjax").show('slow');
                $("#listaProductosAjax").html(data);
            }
        });
           
    });

    // Funcion de validación que evita que se envie formulario vacio
    $('form').submit(function(e){
        if ($('input[type=checkbox]:checked').length === 0) {
            e.preventDefault();
            alerta_no();
        }
    });

    // Funcion de validación para alertar del error
    function alerta_no(){
        Swal.fire(
            'Oops...',
            'Es necesario seleccionar almenos 1 producto',
            'error'
        )
    }

    // Funcion
    /*
    function guarda(){
        var escala_id = $('#escala_id').val();
        var precio = $('#precio').val();
        var productos = $('#producto_id[]').val();
        alert(productos);
        $.ajax({
            type:'GET',
            url:"{{ url('Escala/ajax_producto') }}",
            data: {
                escala_id : escala_id, precio : precio, 'array': JSON.stringify(productos)
            },
            success:function(data){
                $("#listaProductosAjax").show('slow');
                $("#listaProductosAjax").html(data);
            }
        });
    }
    */
</script>
@endsection