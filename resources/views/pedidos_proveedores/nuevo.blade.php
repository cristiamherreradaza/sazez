@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<form action="{{ url('PedidosProveedore/guarda') }}" method="POST" id="formularioPedido">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card border-primary">                                
                <div class="card-header bg-primary">
                    <h4 class="mb-0 text-white">NUEVO PEDIDO PROVEEDOR</h4>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Buscar Producto</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="termino" name="termino">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="ti-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Proveedor</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <select name="proveedor" id="proveedor" class="form-control">
                                    @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}"> {{ $proveedor->nombre }} </option>
                                    @endforeach
                                </select>
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
        <div class="col-md-12">
            <div class="card border-dark">
                <div class="card-header bg-dark">
                    <h4 class="mb-0 text-white">PRODUCTOS A PEDIR</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table id="tablaPedido" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Marca</th>
                                    <th>Tipo</th>
                                    <th>Modelo</th>
                                    <th>Colores</th>
                                    <th>Stock</th>
                                    <th>Cantidad por Caja</th>
                                    <th style="width: 5%">Cantidad</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="validaItems()">REGISTRA PEDIDO</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('js/NumeroALetras.js') }}"></script>
<script>
    // Funcion para el uso de ajax
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Funcion que habilita el datatable
    var t = $('#tablaPedido').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        info:false,
        language: {
            url: '{{ asset('datatableEs.json') }}'
        }
    });

    // Funcion Ajax que busca el producto y devuelve coincidencias
    $(document).on('keyup', '#termino', function(e) {
        termino_busqueda = $('#termino').val();
        almacen = $('#almacen').val();
        if (termino_busqueda.length > 2) {
            $.ajax({
                url: "{{ url('PedidosProveedore/ajaxBuscaProducto') }}",
                data: {
                    termino: termino_busqueda,
                    almacen: almacen
                    },
                type: 'POST',
                success: function(data) {
                    $("#listadoProductosAjax").show('slow');
                    $("#listadoProductosAjax").html(data);
                }
            });
        }
    });

    // Funcion que elimina un producto en la lista de productos ingresados
    var itemsPedidoArray = [];
    $(document).ready(function () {
        $('#tablaPedido tbody').on('click', '.btnElimina', function () {
            t.row($(this).parents('tr'))
                .remove()
                .draw();
            let itemBorrar = $(this).closest("tr").find("td:eq(0)").text();
            let pos = itemsPedidoArray.lastIndexOf(itemBorrar);
            itemsPedidoArray.splice(pos, 1);
        });
    });

    function validaItems()
    {
        if ($("#formularioPedido")[0].checkValidity()) {
            $("#formularioPedido").submit();
            Swal.fire({
                type: 'success',
                title: 'Excelente',
                text: 'Se registro el pedido'
            })
        }else{
            $("#formularioPedido")[0].reportValidity();
        }
    }

</script>
@endsection