@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
<form action="{{ url('Combo/guarda') }}" class="needs-validation" method="POST" novalidate>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card border-info">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">NUEVO COMBO</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input type="text" name="nombre_combo" id="nombre_combo" class="form-control" required>
                            </div>                    
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Fecha Inicio</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                            </div>                    
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Fecha Final</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input type="date" name="fecha_final" id="fecha_final" class="form-control" required>
                            </div>                    
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">Buscar producto</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="termino" name="termino">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="ti-search"></i></span>
                                    </div>
                                </div>
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
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="mb-0 text-white">PRODUCTOS EN COMBO</h4>
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
                                    <th>Precio estandar</th>
                                    <th style="width: 8%">Precio ofertado</th>
                                    <th style="width: 5%">Cantidad</th>
                                    <th class="w-10 text-center">Importe</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="10" class="text-right">TOTAL</th>
                                    <th colspan="2"><input type="text" class="form-control text-right" name="totalCompra"
                                            id="resultadoSubTotales" style="width: 120px;" readonly></th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="form-group">
                            <button type="submit" class="btn waves-effect waves-light btn-block btn-success">GUARDAR COMBO</button>
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
<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/NumeroALetras.js') }}"></script>
<script>
     var t = $('#tablaPedido').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        info:false,
        language: {
            url: '{{ asset('datatableEs.json') }}'
        }
    });
    var itemsPedidoArray = [];
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
            let pos = itemsPedidoArray.lastIndexOf(itemBorrar);
            itemsPedidoArray.splice(pos, 1);
            sumaSubTotales();
        });
    });

    $(document).on('keyup change', '.precio', function(e){
        let precio = Number($(this).val());
        let id = $(this).data("id");
        let cantidad = Number($("#cantidad_"+id).val());
        let subtotal = precio*cantidad;
        $("#subtotal_"+id).val(subtotal);
        sumaSubTotales();
    });

    $(document).on('keyup change', '.cantidad', function(e){
        // alert("cambio");
        let cantidad = Number($(this).val());
        let id = $(this).data("id");
        let precio = Number($("#precio_"+id).val());
        let subtotal = precio*cantidad;
        console.log(precio);
        $("#subtotal_"+id).val(subtotal);
        sumaSubTotales();
    });

    function sumaSubTotales()
    {
        let sum = 0;
        $('.subtotal').each(function(){
            sum += parseFloat(this.value);
        });
        // sumaVisible = sum.toLocaleString('en', {useGrouping:true});
        
        $("#resultadoSubTotales").val(sum);
        valorLiteral = numeroALetras(sum, {
            plural: 'Bolivianos',
            singular: 'Bolivianos',
            centPlural: 'Centavos',
            centSingular: 'Centavo'
        });
        $("#montoLiteral").html(valorLiteral);
        // console.log(valor);
    }

    $(document).on('keyup', '#termino', function(e) {
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 3) {
            $.ajax({
                url: "{{ url('Combo/ajaxBuscaProducto') }}",
                data: {termino: termino_busqueda},
                type: 'POST',
                success: function(data) {
                    $("#listadoProductosAjax").show('slow');
                    $("#listadoProductosAjax").html(data);
                }
            });
        }

    });

    function adicionaPedido(item)
    {
        /*var item = $("#item_"+item).closest("tr").find('td').each(function(){
            console.log(this.text);
        });*/
        var item = $("#item_"+item).closest("tr").find('td').text();
        console.log(item);
    }

    function eliminar_pedido()
    {
        var id = $("#id_pedido").val();
        Swal.fire({
            title: 'Estas seguro de eliminar este pedido?',
            text: "Luego no podras recuperarlo!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, estoy seguro!',
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Excelente!',
                    'El Pedido fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Combo/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
