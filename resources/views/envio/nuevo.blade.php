@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<link href="{{ asset('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />

    <form action="{{ url('Envio/guarda') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card border-info">                                
                    <div class="card-header bg-info">
                        <h4 class="mb-0 text-white">ENVIO DE PRODUCTOS</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">Fecha</label>
                                    <input type="date" name="fecha_pedido" id="fecha_pedido" class="form-control" value="{{ date("Y-m-d") }}" required>
                                </div>
                            </div>
                            @if(auth()->user()->perfil_id == 1)
                                <div class="col">
                                    <div class="form-group">
                                        <label class="control-label">Almacen Origen</label>
                                        <select name="almacen_origen" id="almacen_origen" class="form-control">
                                            @foreach($almacenes as $almacen)
                                                @if($almacen->id == auth()->user()->almacen_id)
                                                    <option value="{{ $almacen->id }}" selected> {{ $almacen->nombre }} </option>
                                                @else
                                                    <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">Almacen Destino</label>
                                    <select name="almacen_a_pedir" id="almacen_a_pedir" class="form-control">
                                        @foreach($almacenes as $almacen)
                                            @if($almacen->id != auth()->user()->almacen_id)
                                                <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                            @endif
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
                        <h4 class="mb-0 text-white">PRODUCTOS PARA ENVIO</h4>
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
                                        <th>Stock Origen</th>
                                        <th style="width: 5%">Cantidad</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="validaItems()">ENVIAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-primary">
                <div class="card-header bg-primary" onclick="muestra_formulario_importacion()">
                    <h4 class="mb-0 text-white">IMPORTAR EXCEL PRODUCTOS</h4>
                </div>
                <div class="card-body" id="bloque_formulario_importacion" style="display: none;">
                    <form method="post" enctype="multipart/form-data" id="upload_form" class="upload_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">ARCHIVO</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="select_file" id="select_file" class="custom-file-input"  accept=".xlsx" required>
                                            <label class="custom-file-label" for="inputGroupFile01">Seleccione...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <input type="submit" name="upload" id="upload" class="btn btn-success waves-light btn-block float-lg-right" value="Importar archivo excel">
                                {{--  <button type="submit" id="btnEnviaExcel" onclick="enviaExcel();"
                                    class="btn waves-effect waves-light btn-block btn-success">Importar archivo
                                    excel</button> --}}
                                <button class="btn btn-primary btn-block" type="button" id="btnTrabajandoExcel"
                                    disabled style="display: none;">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    &nbsp;&nbsp;Estamos trabajando, ten paciencia ;-)
                                </button>

                            </div>
                            <div class="col-md-3">
                                <a href="{{ asset('excels/muestra_excel_envios.xlsx') }}" rel="noopener noreferrer">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-warning">Descargar formato excel</button>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <span style="background-color: #ea6274; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                Colocar NT en caso de no tener informacion
                            </div>
                            <div class="col-md-3">
                                <span style="background-color: #67ff67; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                Solo introducir numeros
                            </div>
                            <div class="col-md-3">
                                <span style="background-color: #8065a9; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                Colocar 0 si no tienen el dato
                            </div>
                            <div class="col-md-3">
                                <span style="background-color: #62adea; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                No dejar celdas vacias ni cambiar el orden
                            </div>
                            <div class="col-md-12">
                                <span class='zoom' id='ex1'>
                                    <img src="{{ asset('assets/images/muestra_excel_envios_productos.png') }}" class="img-thumbnail" alt='Daisy on the Ohoopee' />
                                </span>
                            </div>
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
    // Funcion que muestra/oculta caja de importacion/exportacion de envio excel
    function muestra_formulario_importacion()
    {
        $("#bloque_formulario_importacion").toggle('slow');
    }

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
        almacen_origen = $('#almacen_origen').val();
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 2) {
            $.ajax({
                url: "{{ url('Envio/ajaxBuscaProductos') }}",
                data: {
                    almacen_origen: almacen_origen,
                    termino: termino_busqueda
                    },
                type: 'POST',
                success: function(data) {
                    $("#listadoProductosAjax").show('slow');
                    $("#listadoProductosAjax").html(data);
                }
            });
        }
    });
    
    // Variable necesaria para funcionamiento de datatable
    var itemsPedidoArray = [];
    
    // Funcion que valida que exista al menos un item en la lista para continuar
    function validaItems()
    {
        if(itemsPedidoArray.length > 0){
            //alert('ok');
        }else{
            event.preventDefault();
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Tienes que adicionar al menos un producto.'
            })
        }        
    }

    // Funcion que agrega un item de la lista de productos buscados
    function adicionaPedido(item)
    {
        var item = $("#item_"+item).closest("tr").find('td').text();
        console.log(item);
    }

    // Funcion que elimina un producto en la lista de productos ingresados
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

    // Script de importacion de excel
    $(document).ready(function() {
        $('.upload_form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ url('Entrega/ajax_importar') }}",
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data)
                {
                    if(data.sw == 1){
                        Swal.fire(
                        'Hecho',
                        data.message,
                        'success'
                        )
                        .then(function() {
                            window.location.href = "{{ url('Envio/listado') }}";
                        });
                    }else{
                        Swal.fire(
                        'Oops...',
                        data.message,
                        'error'
                        )
                    }
                }
            })
        });
    });

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
                    window.location.href = "{{ url('Envio/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection