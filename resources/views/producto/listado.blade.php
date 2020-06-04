@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
<div id="divmsg" style="display:none" class="alert alert-primary" role="alert"></div>
<div class="row">
    <!-- Column -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">LISTADO DEL PRODUCTOS </h4>
                {{-- <div class="table-responsive m-t-40"> --}}
                <div class="table-responsive">
                <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
                    <thead>
                        <tr>
                            <th>COD</th>
                            <th>NOMBRE</th>
                            <th>TIPO</th>
                            <th>MARCA</th>
                            <th>COLOR</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
@stop
@section('js')
    <script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>
$.ajaxSetup({
    // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    //  console.log('testOne');     para debug, ayuda a ver hasta donde se ejecuta la funcion
    // Setup - add a text input to each footer cell
    // $('#example tfoot th').each( function () {
    //     var title = $(this).text();
    //     $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
    // } );

    // DataTable
    var table = $('#tabla-usuarios').DataTable( {
        iDisplayLength: 10,
        processing: true,
        // "scrollX": true,
        serverSide: true,
        ajax: "{{ url('Producto/ajax_listado') }}",
        columns: [
            {data: 'codigo', name: 'codigo'},
            {data: 'nombre', name: 'nombre'},
            {data: 'tipo', name: 'tipos.nombre'},
            {data: 'marca', name: 'marcas.nombre'},
            {data: 'colores', name: 'colores'},
            {data: 'action'},
        ],
        language: {
            url: '{{ asset('datatableEs.json') }}'
        },
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

function edita_producto(producto_id)
{
    // console.log(producto_id);
    window.location.href = "{{ url('Producto/edita') }}/" + producto_id;
}

function muestra_producto(producto_id)
{
    window.location.href = "{{ url('Producto/muestra') }}/" + producto_id;
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