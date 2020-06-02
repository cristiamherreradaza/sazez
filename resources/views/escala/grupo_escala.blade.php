@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
{{--   <link rel='stylesheet' href='https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.css'>
<link rel='stylesheet' href='https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css'>
<link rel='stylesheet' href='https://www.gyrocode.com/wp/wp-content/cache/minify/7db04.css'> --}}
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')


<div class="card card-outline-info">
    <form action="{{ url('Escala/guarda_multiple') }}" class="needs-validation" method="POST">
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
                    <h4 class="mb-0 text-white">GRUPO DE ESCALAS</h4>
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
                                    @foreach($tipos as $tip)
                                    <option value="{{ $tip->id }}"> {{ $tip->nombre }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Escalas
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                </label>
                                <select name="escala_id" id="escala_id" class="form-control" autofocus required>
                                    <option value=""> Seleccionar </option>
                                    @foreach($escalas as $esca)
                                    <option value="{{ $esca->id }}"> {{ $esca->nombre }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Precio Bs.
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                </label>
                                <input type="number" name="precio" id="precio" class="form-control" size="10" min="1" max="100000" pattern="^[0-9]+" step="any" autofocus required>
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


{{-- <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
<script src='https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.js'></script>
<script src='https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js'></script>
 --}}
<script>

    $('#tipo_id').on('change', function(e){
            var tipo = e.target.value;
            $.ajax({
            type:'GET',
            url:"{{ url('Escala/ajax_producto') }}",
            data: {
                tipo : tipo
            },
            success:function(data){
                $("#listaProductosAjax").show('slow');
                $("#listaProductosAjax").html(data);
            }
        });
           
    });

</script>
<script>
    $('form').submit(function(e){
        if ($('input[type=checkbox]:checked').length === 0) {
            e.preventDefault();
            alerta_no();
        }
    });

    function alerta_no(){
        Swal.fire(
                'Oops...',
                'Es necesario seleccionar almenos 1 producto',
                'error'
                )
    }
</script>

<script>

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
</script>
@endsection