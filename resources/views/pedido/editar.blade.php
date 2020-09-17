@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<link href="{{ asset('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />

    <form action="{{ url('Pedido/guarda') }}" method="POST">
        @csrf
    
        <div class="row">
            <div class="col-md-12">
                <div class="card border-info">                                
                    <div class="card-header bg-info">
                        <h4 class="mb-0 text-white">EDITAR PEDIDO</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Fecha</label>
                                    <input type="date" name="fecha_pedido" id="fecha_pedido" class="form-control" value="{{ $pedido->fecha }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Almacen a solicitar</label>
                                    <select name="almacen_a_pedir" id="almacen_a_pedir" class="form-control">
                                        {{-- <option value="{{ $pedido->almacene_id }}"> {{ $pedido->almacen->nombre }} </option> --}}
                                        @foreach($almacenes as $almacene)
                                        <option value="{{ $almacene->id }}"> {{ $almacene->nombre }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

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
                        <h4 class="mb-0 text-white">PRODUCTOS PARA PEDIDO</h4>
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
                                        <th style="width: 5%">Cantidad</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <button type="submit" class="btn waves-effect waves-light btn-block btn-success">GUARDAR PEDIDO</button>
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
        });
    });


    $(document).on('keyup', '#termino', function(e) {
        termino_busqueda = $('#termino').val();
        almacen_id = $('#almacen_a_pedir').val();
        if (termino_busqueda.length > 2) {
            $.ajax({
                url: "{{ url('Pedido/ajaxBuscaProducto') }}",
                data: {termino: termino_busqueda, almacen : almacen_id},
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

</script>
@endsection