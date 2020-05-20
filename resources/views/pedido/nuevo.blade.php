@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
@endsection

@section('content')


<div class="card card-outline-info">
    <div class="card-header">
        <h4 class="mb-0 text-white">
            PEDIDO NUEVO
        </h4>        
    </div>
    <div class="card-body">
        <form action="{{ url('Pedido/guarda') }}" method="POST">
            @csrf
            <div class="row">
                <input type="hidden" name="id_pedido" id="id_pedido">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Numero</label>
                        <input type="text" name="numero_pedido" id="numero_pedido" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Fecha</label>
                        <input type="date" name="fecha_pedido" id="fecha_pedido" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Almacen a solicitar</label>
                        <select name="almacen_a_pedir" id="almacen_a_pedir" class="form-control">
                            @foreach($almacenes as $almacen)
                            <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="submit" class="btn waves-effect waves-light btn-block btn-success"
                            onclick="guardar_pedido()">CONFIRMAR</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" class="btn waves-effect waves-light btn-block btn-danger"
                            onclick="eliminar_pedido()">DESCARTAR</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-primary">                                
                <div class="card-header">
                    <h4 class="mb-0 text-white">BUSCADOR DE PRODUCTOS</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="id_pedido" id="id_pedido">
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Nombre producto</span>
                                </div>
                                <input type="text" class="form-control" id="termino" name="termino" aria-describedby="basic-addon3">
                            </div>
                            <div id="listadoProductosAjax">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-warning">
                <div class="card-header">
                    <h4 class="mb-0 text-white">PRODUCTOS PARA PEDIDO</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table id="tablaPedido" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Opciones</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <button type="button" class="btn btn-warning" title="Editar marca"
                                            onclick="editar()"><i
                                                class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger" title="Eliminar marca"
                                            onclick="eliminar()"><i
                                                class="fas fa-trash"></i></button>
                                    </td> --}}
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@stop

@section('js')
<script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<!-- Sweet-Alert  -->
<script src="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweet-alert.init.js') }}"></script>

<script>
    var t = $('#tablaPedido').DataTable();
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
         $('#tablaPedido tbody').on('click', '.btnElimina', function () {
            // console.log('entro');
            t.row($(this).parents('tr'))
                .remove()
                .draw();
        });
    });


    $(document).on('keyup', '#termino', function(e) {
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 4) {
            // console.log(termino_busqueda);
            $.ajax({
                url: "{{ url('Pedido/ajaxBuscaProducto') }}",
                data: {termino: termino_busqueda},
                type: 'POST',
                success: function(data) {
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