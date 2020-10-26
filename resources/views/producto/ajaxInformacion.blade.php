<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
<h3>
    Nombre: <span class="text-info">{{ $datosProducto->nombre }}</span>
</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead class="bg-info text-white">
            <tr>
                <th scope="col">ALMACEN</th>
                <th scope="col">EXISTENCIAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cantidadTotal as $ct)
            <tr>
                <td scope="col">{{ $ct->almacen }}</td>
                <td scope="col">{{ $ct->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="ajaxActualizaPrecios">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th scope="col">ESCALA</th>
                    <th scope="col">PRECIO VENTA</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($precios as $p)
                <tr>
                    <td scope="col">{{ $p->escala->nombre }}</td>
                    <td scope="col">{{ $p->precio }}</td>
                    <td scope="col">
                        <button class="btn btn-danger" onclick="elimina_precio_escala('{{ $p->id }}', '{{ $p->producto->id }}')" title="Elimina precio"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="precios_producto_id" id="precios_producto_id" value="{{ $datosProducto->id }}">

        <select class="select2 form-control custom-select" name="precios_escala" id="precios_escala" style="width: 100%; height:36px;">
            @foreach ($escalas as $e)
                <option value="{{ $e->id }}">{{ $e->nombre }}</option>
            @endforeach
        </select>        

    </div>
    <div class="col-md-3">
        <input type="number" name="importePrecios" id="importePrecios" class="form-control" value="1" min="1">
    </div>
    <div class="col-md-3">
        <button type="button" class="btn waves-effect waves-light btn-success" id="btnAjaxAdicionaPrecio" onclick="adiciona_precio()"><i class="fas fa-plus-circle"></i></button>    
    </div>
</div>

<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/forms/select2/select2.init.js') }}"></script>

<script type="text/javascript">
    $('#precios_escala').select2({
        dropdownParent: $('#detalle_producto')
    });

    function adiciona_precio()
    {
        let escala   = $("#precios_escala").val();
        let producto = $("#precios_producto_id").val();
        let precio   = $("#importePrecios").val();
        $("#btnAjaxAdicionaPrecio").hide();

        $.ajax({
            url: "{{ url('Producto/ajaxGuardaPrecio') }}",
            data: {escala: escala, productoId: producto, precio: precio},
            type: 'GET',
            success: function(data) {
                $("#ajaxActualizaPrecios").load("{{ url('Producto/ajaxMuestraPrecios') }}/"+producto);
                $("#btnAjaxAdicionaPrecio").show();
            }
        });
    }

    function elimina_precio_escala(precio, productoId)
    {
        $.ajax({
            url: "{{ url('Producto/ajaxEliminaPrecios') }}",
            data: {precioId: precio},
            type: 'GET',
            success: function(data) {
                $("#ajaxActualizaPrecios").load("{{ url('Producto/ajaxMuestraPrecios') }}/"+productoId);
            }
        });
    }
</script>