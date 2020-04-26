@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('content')
<div id="divmsg" style="display:none" class="alert alert-primary" role="alert"></div>
<div class="row">
    <!-- Column -->
    
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">LISTADO DEL PERSONAL </h4>
                {{-- <div class="table-responsive m-t-40"> --}}
                    <table id="tabla-usuarios" class="table table-bordered table-striped no-wrap">
                        <thead>
                            <tr>
                                <th>Ap Paterno</th>
                                <th>Ap Materno</th>
                                <th>Nombres</th>
                                <th>C.I.</th>
                                <th>Rol</th>
                                <th>Incorporacion</th>
                                <th>Celular</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                {{-- </div> --}}
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
@stop
@section('js')
<script src="{{ asset('/assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js')}}"></script>
<script>
    // $(function () {
    //     $('#config-table').DataTable({
    //         responsive: true,
    //         "order": [
    //             [0, 'asc']
    //         ],
    //         "language": {
    //             "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    //         }
    //     });

    // });

$(document).ready(function() {
    //  console.log('testOne');     para debug, ayuda a ver hasta donde se ejecuta la funcion
    // Setup - add a text input to each footer cell
    // $('#example tfoot th').each( function () {
    //     var title = $(this).text();
    //     $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
    // } );

    // DataTable
    var table = $('#tabla-usuarios').DataTable( {
        "iDisplayLength": 10,
        "processing": true,
        // "scrollX": true,
        "serverSide": true,
        "ajax": "{{ url('User/ajax_listado') }}",
        "columns": [
            {data: 'apellido_paterno'},
            {data: 'apellido_materno'},
            {data: 'nombres'},
            {data: 'cedula'},
            {data: 'tipo_usuario'},
            {data: 'fecha_incorporacion'},
            {data: 'numero_celular'},
            {data: 'action'},
        ]
    } );

    // Apply the search
    // table.columns().every( function () {
    //     var that = this;

    //     $( 'input', this.footer() ).on( 'keyup change clear', function () {
    //         if ( that.search() !== this.value ) {
    //             that
    //                 .search( this.value )
    //                 .draw();
    //         }
    //     } );
    // } );

} );

function asigna_materias(user_id)
{
    // console.log(user_id);
    window.location.href = "{{ url('User/asigna_materias') }}/" + user_id;
}

</script>

<script type="text/javascript">


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
