@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('content')
<div id="divmsg" style="display:none" class="alert alert-primary" role="alert"></div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="mb-0 text-white">LISTA DE DOCENTES</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive m-t-0">
                            <table id="tabla-docentes" class="table display table-bordered table-striped no-wrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nomina</th>
                                        <th>Nombres y Apellidos</th>
                                        <th>CI</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $user->nomina }}</td>
                                            <td>{{ $user->nombres }} {{ $user->apellido_paterno }} {{ $user->apellido_materno }}</td>
                                            <td>{{ $user->cedula }}</td>
                                            <td>1</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th>#</th>
                                    <th>Nomina</th>
                                    <th>Nombres y Apellidos</th>
                                    <th>CI</th>
                                    <th>Opciones</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>        
    </div>
</div>
@stop
@section('js')
<script src="{{ asset('/assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js')}}"></script>

<script type="text/javascript">

    $(document).ready(function(){
        var table = $('#tabla-docentes').DataTable( {
        "iDisplayLength": 10,
        "processing": true,
        "scrollX": true
    } );
    });

    // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // al hacer clic en el boton GUARDAR, se procedera a la ejecucion de la funcion
    $(".btnenviar").click(function(e){
        e.preventDefault();     // Evita que la página se recargue
        var nombre = $('#nombre').val();    
        var nivel = $('#nivel').val();
        var semestre = $('#semestre').val();

        $.ajax({
            type:'POST',
            url:"{{ url('carrera/store') }}",
            data: {
                nom_carrera : nombre,
                desc_niv : nivel,
                semes : semestre
            },
            success:function(data){
                mostrarMensaje(data.mensaje);
                limpiarCampos();
            }
        });
    });
</script>
@endsection
