@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<form action="{{ url('Movimiento/guarda') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card border-info">                                
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">NUEVO INGRESO</h4>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-2" id="ventana_almacen">
                            <div class="form-group">
                                <label class="control-label">Incluir Central</label>
                                <select name="incluye_almacen" id="incluye_almacen" class="form-control">
                                    <option value="No" selected> No </option>
                                    <option value="Si"> Si </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Sucursal</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <select name="almacen" id="almacen" class="form-control">
                                    @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                    @endforeach
                                </select>
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

                        <div class="col-md-4">
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
                    <h4 class="mb-0 text-white">PRODUCTOS A INGRESAR</h4>
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
                                    <th>Total Cantidad</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="validaItems()">GUARDAR INGRESO</button>
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
    // Funcion para habilitar/deshabilitar el input de Incluir Almacen Central
    $( function() {
        $("#incluye_almacen").prop('disabled', true);
        $("#incluye_almacen").val("No");
        $("#almacen").change( function() {
            if($(this).val() != 1){
                $("#incluye_almacen").prop('disabled', false);
            }else{
                $("#incluye_almacen").prop('disabled', true);
                $("#incluye_almacen").val("No");
            }
        });
    });

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
        });
    });

    $(document).on('keyup change', '.precio', function(e){
        let precio = Number($(this).val());
        if(precio == 0){
            precio = 1;
        }
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
        if(precio == 0){
            precio = 1;
        }
        let subtotal = precio*cantidad;
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
        if (termino_busqueda.length > 2) {
            $.ajax({
                url: "{{ url('Movimiento/ajaxBuscaProducto') }}",
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
                    window.location.href = "{{ url('Pedido/eliminar') }}/"+id;
                });
            }
        })
    }

    function validaItems()
    {
        if(itemsPedidoArray.length > 0){
            //alert(itemsPedidoArray[0].precio);
            // Swal.fire({
            //     type: 'success',
            //     title: 'Excelente',
            //     text: 'Se realizo el ingreso'
            // })
        }else{
            event.preventDefault();
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Tienes que adicionar al menos un producto.'
            })
        }        
    }

</script>
@endsection