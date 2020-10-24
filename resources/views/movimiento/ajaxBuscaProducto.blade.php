<div class="table-responsive">
    <table class="table table-striped no-wrap" id="tablaProductosEncontrados">
        <thead>
            <tr>
                <th>ID</th>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Modelo</th>
                <th>Colores</th>
                <th>Stock</th>
                <th>Precio</th>
                <th class="text-nowrap">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $key => $p)
            @php
                // sacamos los precios de los productos
                $preciosProductos = App\Precio::where('producto_id', $p->id)
                                    ->where('precio', '<>', 0)
                                    ->get();
                $contadorPrecios = 0;
                foreach ($preciosProductos as $pep) {
                    $arrayPreciosProductos[$contadorPrecios]["escala_id"] = $pep->escala->id;
                    $arrayPreciosProductos[$contadorPrecios]["nombre"]    = $pep->escala->nombre;
                    $arrayPreciosProductos[$contadorPrecios]["minimo"]    = $pep->escala->minimo;
                    $arrayPreciosProductos[$contadorPrecios]["maximo"]    = $pep->escala->maximo;
                    $arrayPreciosProductos[$contadorPrecios]["precio"]    = $pep->precio;
                    $contadorPrecios++;
                }
                $arrayPreciosProductosJson = json_encode($arrayPreciosProductos);
            @endphp
                <tr class="item_{{ $p->id }}">
                    <td>{{ $p->id }}</td>
                    <td>
                        {{ $p->codigo }}
                        <input type="hidden" id="preciosEscalas_{{ $p->id }}" name="preciosEscalas_{{ $p->id }}" value="{{ $arrayPreciosProductosJson }}">
                    </td>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->marca->nombre }}</td>
                    <td>{{ $p->tipo->nombre }}</td>
                    <td>{{ $p->modelo }}</td>
                    <td>{{ $p->colores }}</td>
                    @php
                        $cantidadTotal = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) - SUM(salida) as total'))
                            ->where('producto_id', $p->id)
                            ->where('almacene_id', $almacen_id)
                            ->first();
                        $cantidadTotal=intval($cantidadTotal->total);
                    @endphp
                    <td>{{ $cantidadTotal }}</td>
                    @php
                        $precio = App\Precio::where('producto_id', $p->id)
                                    ->where('escala_id', 1)
                                    ->first();
                    @endphp
                    <td>{{ $precio->precio }}</td>
                    <td>
                        <button type="button" class="btnSelecciona btn btn-info" title="Adiciona Item"><i class="fas fa-plus"></i></button>
                    </td>
                </tr>    
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $("#tablaProductosEncontrados").on('click', '.btnSelecciona', function () {

            $("#listadoProductosAjax").hide('slow');
            $("#termino").val("");
            $("#termino").focus();

            var currentRow = $(this).closest("tr");

            var id      = currentRow.find("td:eq(0)").text();
            var codigo  = currentRow.find("td:eq(1)").text();
            var nombre  = currentRow.find("td:eq(2)").text();
            var marca   = currentRow.find("td:eq(3)").text();
            var tipo    = currentRow.find("td:eq(4)").text();
            var modelo  = currentRow.find("td:eq(5)").text();
            var colores = currentRow.find("td:eq(6)").text();
            var stock   = currentRow.find("td:eq(7)").text();

            precios = $("#preciosEscalas_"+id).val();

            let buscaItem = itemsPedidoArray.lastIndexOf(id);
            if(buscaItem < 0)
            {
                itemsPedidoArray.push(id);  
                t.row.add([
                    id,
                    codigo,
                    nombre,
                    marca,
                    tipo,
                    modelo,
                    colores,
                    stock,
                    '<select class="form-control" name="escala_id_m['+id+']" id="escala_m_'+id+'" onchange="cambiaPrecioM('+id+')"></select><input type="hidden" name="cantidad_escala_m['+id+']" id="cantidad_escala_m_'+id+'" value="1"><input type="hidden" name="producto_id['+id+']" id="producto_id_'+id+'" value="'+id+'">',
                    `<input type="number" class="form-control text-right cantidad" name="cantidad[`+id+`]" id="cantidad_`+id+`" value="1" data-id="`+id+`" min="1" pattern="^[0-9]+" required>`,
                    '<button type="button" class="btnElimina btn btn-danger" title="Eliminar producto"><i class="fas fa-trash-alt"></i></button>'
                ]).draw(false);
                adicionaItemUnidad(precios, id);
            }
        });

    });

     // funcion para llenar el combo de los productos al por mayor
    function adicionaItemUnidad(precios, productoId)
    {
        let objetoPrecios = JSON.parse(precios);
        for (let [key, value] of Object.entries(objetoPrecios)) {
            $('#escala_m_'+productoId).append(`<option value="`+value.escala_id+`" data-cantidad="`+value.minimo+`">`+value.nombre+`</option>`);
        }
    }

    function cambiaPrecioM(productoId)
    {
        // let precio = $("#escala_m_"+productoId).find(':selected').data('precio');
        let cantidadEscala = $("#escala_m_"+productoId).find(':selected').data('cantidad');
        // $("#precio_m_"+productoId).val(precio);
        // $("#precio_venta_m_"+productoId).val(precio);
        // let cantidadMayor = Number($("#cantidad_m_"+productoId).val());
        // let precioMayor = Number($("#precio_m_"+productoId).val());
        // let subtotalMayor = precioMayor*cantidadMayor;
        // $("#subtotal_m_"+productoId).val(subtotalMayor);
        $("#cantidad_escala_m_"+productoId).val(cantidadEscala);
        // sumaSubTotales();
    }


</script>