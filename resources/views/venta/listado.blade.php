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
                <h4 class="card-title">LISTADO DEL VENTAS </h4>
                <div class="table-responsive">
                    <table id="tabla-ventas" class="table table-striped table-bordered table-hover no-wrap" style="width: 100%;">
                        <thead  class="table-info">
                            <tr>
                                <th>ID</th>
                                <th>TIENDA</th>
                                <th>USUARIO</th>
                                <th>CLIENTE</th>
                                <th>TOTAL</th>
                                <th>SALDO</th>
                                <th>FECHA</th>
                                <th>VENTA<br>FACTURADA</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
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
    var table = $('#tabla-ventas').DataTable( {
        iDisplayLength: 10,
        processing: true,
        // "scrollX": true,
        serverSide: true,
        stateSave: false,
        //autoWidth: true,
        ajax: "{{ url('Venta/ajax_listado') }}",
        "order": [[ 0, "desc" ]],
        columns: [
            {data: 'id', name: 'id', className: 'text-center'},
            {data: 'almacene', name: 'almacenes.nombre', className: 'text-center'},
            {data: 'nombre_usuario', name: 'usuario.name', className: 'text-center'},
            {data: 'user', name: 'users.name', className: 'text-center'},
            {data: 'total', name: 'total', className: 'text-center'},
            {data: 'saldo', name: 'saldo', className: 'text-center'},
            {data: 'fecha', name: 'fecha', className: 'text-center'},
            {data: function ( row, type, val, meta ) {

                        let factSiNo =  null;
                        if(row.factura_id  != null ){
                            factSiNo = '<button onclick="imprimirFactura(' + row.id + ')" class="btn btn-inverse" title="Venta FACTURADA Imprimir FACTURA"><i class="fas fa-print"></i></button>';
                        }
                        return factSiNo;
                    },
                    className: 'text-center', //autoWidth: true,
                    name: 'factura_id', visible: true , searchable: false},
            {data: 'action', className: 'text-center'},
        ],
        language: {
            url: '{{ asset('datatableEs.json') }}'
        },
        createdRow: function (row, data, index) {
            //
            // if the second column cell is blank apply special formatting
            //
            //console.log(data);
            if (data['factura_id'] != null) {
                //console.log(row);
                console.log(row);
                console.log(data);
                console.log(index);
                $(row).addClass('table-warning');
                //$(row).css({"background-color": "rgba(57,196,73,60%)"});

            }
        }
    } );
    //table.autoWidth(true);
    console.log(table);
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

/*function edita_producto(producto_id)
{
    // console.log(producto_id);
    window.location.href = "{{ url('Producto/edita') }}/" + producto_id;
}*/

function muestra(venta_id)
{
    window.location.href = "{{ url('Venta/muestra') }}/" + venta_id;
}

function imprimir(venta_id)
{
    window.location.href = "{{ url('Venta/imprimir') }}/" + venta_id;
}

function pagos(venta_id)
{
    window.location.href = "{{ url('Pago/muestraPagos') }}/" + venta_id;
}

function deuda_total(clienteId)
{
    window.location.href = "{{ url('Pago/deuda_total') }}/" + clienteId;
}
function imprimirFactura(venta_id)
{
    window.open("{{ url('Venta/imprimeFactura') }}/" + venta_id, "_blank");
    return false;
}

</script>

<script type="text/javascript">
    // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    // al hacer clic en el boton GUARDAR, se procedera a la ejecucion de la funcion
    // $(".btnenviar").click(function(e){
    //     e.preventDefault();     // Evita que la página se recargue
    //     var nombre = $('#nombre').val();
    //     var nivel = $('#nivel').val();
    //     var semestre = $('#semestre').val();

    //     $.ajax({
    //         type:'POST',
    //         url:"{{ url('carrera/store') }}",
    //         data: {
    //             nom_carrera : nombre,
    //             desc_niv : nivel,
    //             semes : semestre
    //         },
    //         success:function(data){
    //             mostrarMensaje(data.mensaje);
    //             limpiarCampos();
    //         }
    //     });
    // });
</script>
@endsection
