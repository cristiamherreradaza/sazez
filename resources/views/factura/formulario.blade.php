@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border-info">
            <div class="card-header bg-info">
                <h4 class="mb-0 text-white">GENERACION DE LA FACTURA</h4>
            </div>
            <div class="card-body">
                <form action="{{ url('Factura/guardaVenta') }}" method="POST" id="formularioVenta">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nit</label>
                                <input type="text" name="nit" id="nit" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Razon Social / nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                                <input type="hidden" name="totalVenta" id="totalVenta" />
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Producto</label>
                                <input type="text" name="producto" id="producto" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="control-label">Cantidad</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control" min="1">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Precio</label>
                                <input type="number" name="precio" id="precio" class="form-control" min="1" step="any">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Subtotal</label>
                                <input type="text" name="subtotal" id="subtotal" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <button type="button" onclick="adicionar()" class="btn btn-block btn-success">Adicionar</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="bloqueProductosUnidad">
                            <div class="card border-primary">
                                <div class="card-header bg-primary">
                                    <h4 class="mb-0 text-white">PRODUCTOS POR UNIDAD</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive m-t-40">
                                        <table id="tablaPedido" class="tablesaw table-striped table-hover table-bordered table no-wrap">
                                            <thead>
                                                <tr>
                                                    <th>NOMBRE</th>
                                                    <th>CANTIDAD</th>
                                                    <th>PRECIO</th>
                                                    <th>SUBTOTAL</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>TOTAL</th>
                                                    <th><div id="montoTotal"></div></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        @if ($ultimoParametro != null && $ultimoParametro->estado == 'Activo')
                            <button type="submit" class="btn waves-effect waves-light btn-block btn-success text-white">REGISTRAR VENTA</button>
                        @else
                            <h2 class="text-danger text-center">NO PUEDES FACTURAR POR QUE NO TIENES PARAMETROS ACTIVOS</h2>
                        @endif
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

@stop
@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>

    var totalImporte = 0;

    var cantidad = document.getElementById('cantidad');
    cantidad.addEventListener('keyup', multiplicaCantidad);

    var precio = document.getElementById('precio');
    precio.addEventListener('keyup', multiplicaPrecio);

    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).ready(function () {
        $('#tablaPedido tbody').on('click', '.btnElimina', function () {
            t.row($(this).parents('tr'))
                .remove()
                .draw();
            let itemBorrar = $(this).closest("tr").find("td:eq(0)").text();
        });

    });

    var t = $('#tablaPedido').DataTable({
        paging: false,
        searching: false,
        ordering:  false,   
        info: false,
        language: {
            url: '{{ asset('datatableEs.json') }}'
        },
    });

    function multiplicaCantidad()
    {
        let cantidad = document.getElementById("cantidad").value;
        let precio = document.getElementById("precio").value;
        document.getElementById("subtotal").value=cantidad*precio;
        // console.log(cantidad*precio);
    }

    function multiplicaPrecio()
    {
        let cantidad = document.getElementById("cantidad").value;
        let precio = document.getElementById("precio").value;
        document.getElementById("subtotal").value=cantidad*precio;
    }

    function adicionar()
    {
        let producto = document.getElementById("producto").value;
        let cantidad = document.getElementById("cantidad").value;
        let precio   = document.getElementById("precio").value;
        let subtotal = document.getElementById("subtotal").value;
        
        totalImporte = Number(totalImporte) + Number(subtotal); 
        document.getElementById("montoTotal").innerHTML = totalImporte;
        document.getElementById("totalVenta").value = totalImporte;

        t.row.add([
            `<input type="text" name="producto[]" value="`+producto+`" class="form-control" readonly >`,
            `<input type="text" name="cantidad[]" value="`+cantidad+`" class="form-control text-right" style="width: 80px;" readonly >`,
            `<input type="text" name="precio[]" value="`+precio+`" class="form-control text-right" style="width: 80px;" readonly >`,
            `<input type="text" name="subtotal[]" value="`+subtotal+`" class="form-control text-right" style="width: 80px;" readonly >`,
            '<button type="button" class="btnElimina btn btn-danger" title="Elimina Producto"><i class="fas fa-trash-alt"></i></button>'
        ]).draw(false);
        // console.log(producto);

        document.getElementById("producto").value = "";
        document.getElementById("cantidad").value = "";
        document.getElementById("precio").value = "";
        document.getElementById("subtotal").value = "";

        document.getElementById("producto").focus();
        

    }

</script>

@endsection