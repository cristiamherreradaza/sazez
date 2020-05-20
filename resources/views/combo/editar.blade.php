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
            COMBO
        </h4>        
    </div>
    <div class="card-body">
        <form action="{{ url('Combo/guarda') }}" method="POST" id="formulario_combo">
            @csrf
            <div class="row">         
                <input type="hidden" name="id" id="id" value="{{ $nuevo_combo->id }}">      
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input type="text" name="nombre_combo" id="nombre_combo" class="form-control" value="{{ $nuevo_combo->nombre }}" readonly>
                    </div>                    
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ $nuevo_combo->fecha_inicio }}" readonly>
                    </div>                    
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Fecha Final</label>
                        <input type="date" name="fecha_final" id="fecha_final" class="form-control" value="{{ $nuevo_combo->fecha_final }}" readonly>
                    </div>                    
                </div>
            </div>
        </form>
        <br>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline-primary">                                
                    <div class="card-header">
                        <h4 class="mb-0 text-white">PRODUCTOS DISPONIBLES</h4>
                    </div>
                    <br />  
                    <div class="table-responsive m-t-40">
                        <table id="tabla_productos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Nombre de venta</th>
                                    <th>Marca</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="productos_en_combo">
                <!-- Contenido del datatable productos_combo -->
                
                <!-- Fin del Contenido del datatable productos_combo -->
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
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // DataTable ajax de Productos
    var table = $('#tabla_productos').DataTable( {
        "iDisplayLength": 10,
        "processing": true,
        // "scrollX": true,
        "serverSide": true,
        "ajax": "{{ url('Combo/ajax_listado_producto') }}",
        "columns": [
            {data: 'nombre'},
            {data: 'nombre_venta'},
            {data: 'marca_id'},
            {data: 'action'},
        ],
    } );

    function adicionar_producto_combo(producto_id)
    {
        var combo_id = $("#id").val();
        var producto_id = producto_id;
        $.ajax({
            url: "{{ url('Combo/agregar_combo_producto') }}",
            method: "POST",
            data: {
                combo_id : combo_id,
                producto_id : producto_id
            },
            cache: false,
            success: function(data)
            {
                $("#productos_en_combo").load("{{ url('Combo/lista_combo_productos') }}/"+combo_id);
            }
        })
    }

    function eliminar_combo_producto(combo_id, producto_id)
    {
        // $.post("{{ url('Combo/eliminar_combo_producto') }}",
        // {
        //     combo_id: combo_id,
        //     producto_id: producto_id
        // });
        //  2da
        // e.preventDefault();
        // $.ajax({
        //     url: "{{ url('Combo/eliminar_combo_producto') }}",
        //     method: "POST",
        //     data: {
        //         combo_id : combo_id,
        //         producto_id : producto_id
        //     },
        //     cache: false,
        //     success: function(data)
        //     {
        //         $("#productos_en_combo").load("{{ url('Combo/lista_combo_productos') }}/"+combo_id);
        //     }
        // })
        // 3ra
        //alert('hola');
        Swal.fire({
            title: 'Quieres retirar el producto del combo?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, estoy seguro!',
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type:'GET',
                    url:"{{ url('Combo/elimina_producto') }}/"+combo_id+"/"+producto_id,
                    success:function(data){
                        $("#productos_en_combo").load("{{ url('Combo/lista_combo_productos') }}/"+combo_id);        
                        Swal.fire(
                            'Excelente!',
                            'El producto fue retirado del combo',
                            'success'
                        );
                    }
                });
            }
        })
    }

    // window.onload = function () {
    //     $("#productos_en_combo").html(data);
    // } 

    $(document).ready(function(){
        //código a ejecutar cuando el DOM está listo para recibir instrucciones.
        var id = $("#id").val();
        //alert(id);
        
        // $("#productos_en_combo")
        //        .html("{{ url('Combo/lista_combo_productos/') }}");
        //window.location.href= "{{ url('Combo/lista_combo_productos') }}"/+id;
        //$("#productos_en_combo").load('{{ url('Combo/lista_combo_productos') }}/'+id);
        $("#productos_en_combo").load("{{ url('Combo/lista_combo_productos') }}/"+id);
    }); 


    function checkCampos(numero) {
        if(numero.length <= 0){
            return 0;
        }else{
            return numero;
        }
    }

    function calcula(id)
    {
        var identificador = id;
        var precio = $("#precio-"+id).val();
        precio = checkCampos(precio);
        
        $.ajax({
            type:'POST',
            url:"{{ url('Combo/actualiza_precio') }}",
            data: {
                id : identificador,
                precio : precio
            }
        });
    }















    $(function () {
        $('#myTable').DataTable();
    });



    $('#formulario_combo').on('submit', function (event) {
        event.preventDefault();
        var id = $("#nombre_combo").val();
        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_final = $("#fecha_final").val();


        //var datos_formulario = $(this).serializeArray();
        //var carrera_id = $("#carrera_id").val();

        $.ajax({
            url: "{{ url('Combo/guarda') }}",
            method: "POST",
            data: {
                nombre_combo : nombre_combo,
                fecha_inicio : fecha_inicio,
                fecha_final : fecha_final
            },
            //cache: false,
            success: function (data) {
                $("#productos_ajax_disponibles").html(data);
            }
        })
    });


























    function nueva_categoria()
    {
        $("#nueva_categoria").modal('show');
    }

    function guardar_categoria()
    {
        var nombre_categoria = $("#nombre_categoria").val();
        if(nombre_categoria.length>0){
            Swal.fire(
                'Excelente!',
                'Una nueva categoria fue registrada.',
                'success'
            )
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar el campo Nombre',
                'error'
            )
        }
    }

    function editar(id, nombre)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#editar_categorias").modal('show');
    }

    function actualizar_categoria()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        if(nombre.length>0){
            Swal.fire(
                'Excelente!',
                'Categoria actualizada correctamente.',
                'success'
            )
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar el campo Nombre',
                'error'
            )
        }
        
    }

    function eliminar(id, nombre)
    {
        Swal.fire({
            title: 'Quieres borrar ' + nombre + '?',
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
                    'La categoria fue eliminada',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Categoria/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
