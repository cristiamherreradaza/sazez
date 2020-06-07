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
                <th class="text-right">Stock</th>
                <th class="text-right">Precio</th>
                <th class="text-nowrap"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $hoy = date('Y-m-d');
                $promosArray = [];
            @endphp
            @foreach ($productos as $key => $p)
            @php
                $promo = App\CombosProducto::where('producto_id', $p->id)->get();
                foreach ($promo as $contador => $pro) {
                    $valida = App\Combo::where('id', $pro->combo_id)
                            ->where('fecha_inicio', '<=', $hoy) 
                            ->where('fecha_final', '>=', $hoy)
                            ->first();
                    if ($valida != null) {
                        $promosArray[$contador] = $pro->combo_id;
                    }
                }

                $precioProducto = App\Precio::where('producto_id', $p->id)->where('escala_id', 1)->first();
                $cantidadTotal = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) - SUM(salida) as total'))
                ->where('producto_id', $p->id)
                ->where('almacene_id', auth()->user()->almacen_id)
                ->first();
            @endphp
                <tr class="item_{{ $p->id }}">
                    <td>{{ $p->id }}</td>
                    <td>
                        {{ $p->codigo }}
                        <small id="tags_promos" class="badge badge-default badge-warning form-text text-white" onclick="muestraExistencias({{ $p->id }})">Ver</small>
                    </td>
                    <td>
                        {{ $p->nombre }}
                        @php
                            $contadorPromos = 0;
                        @endphp
                        @forelse ($promosArray as $cpro => $pA)
                            <small id="tags_promos" class="badge badge-default badge-danger form-text text-white" onclick="muestraPromo({{ $pA }})">P {{ ++$contadorPromos }}</small>
                        @empty
                            
                        @endforelse
                    </td>
                    <td>{{ $p->marca }}</td>
                    <td>{{ $p->tipo }}</td>
                    <td>{{ $p->modelo }}</td>
                    <td>{{ $p->colores }}</td>
                    <td><h3 class="text-info text-right">{{ intval($cantidadTotal->total) }}</h3></td>
                    <td><h3 class="text-primary text-right">{{ $precioProducto->precio }}</h3></td>
                    <td>
                        <button type="button" class="btnSelecciona btn btn-success" title="Adiciona Item"><i class="fas fa-plus"></i></button>
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
            var codigo  = currentRow.find("td:eq(1)").html();
            // var nombre  = currentRow.find("td:eq(2)").text();
            var nombre  = currentRow.find("td:eq(2)").html();
            var marca   = currentRow.find("td:eq(3)").text();
            var tipo    = currentRow.find("td:eq(4)").text();
            var modelo  = currentRow.find("td:eq(5)").text();
            var colores = currentRow.find("td:eq(6)").text();
            var stock   = currentRow.find("td:eq(7)").text();
            var precio  = currentRow.find("td:eq(8)").text();

            let buscaItem = itemsPedidoArray.lastIndexOf(id);
            if(buscaItem < 0)
            {
                itemsPedidoArray.push(id);
                t.row.add([
                    codigo,
                    nombre,
                    marca,
                    tipo,
                    stock,
                    `<input type="number" class="form-control text-right precio" name="precio[`+id+`]" id="precio_`+id+`" value="`+precio+`" data-id="`+id+`" step="any" min="1" style="width: 100px;">
                    <input type="hidden" name="precio_venta[`+id+`]" value="`+precio+`">`,
                    `<input type="number" class="form-control text-right cantidad" name="cantidad[`+id+`]" id="cantidad_`+id+`" value="1" data-id="`+id+`" min="1" style="width: 70px;">`,
                    `<input type="number" class="form-control text-right subtotal" name="subtotal[`+id+`]" id="subtotal_`+id+`" value="`+precio+`" step="any" style="width: 120px;" readonly>`,
                    '<button type="button" class="btnElimina btn btn-danger" title="Eliminar marca"><i class="fas fa-trash"></i></button>'
                ]).draw(false);
                sumaSubTotales();
            }
        });

    });

</script>