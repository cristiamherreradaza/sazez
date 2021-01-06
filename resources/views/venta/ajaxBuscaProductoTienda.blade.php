<div class="table-responsive">
    @if ($productos->count()>0)
        
    
    <table class="tablesaw table-striped table-hover table-bordered table no-wrap" id="tablaProductosEncontrados">
        <thead>
            <tr>
                <th>ID</th>
                <th></th>
                <th>CODIGO</th>
                <th>NOMBRE</th>
                <th>MARCA</th>
                <th>TIPO</th>
                <th>MODELO</th>
                <th>COLORES</th>
                <th class="w-10 text-center text-info"><i class="fas fa-archive"></i></th>
                <th class="text-right">PRECIO</th>
                <th class="text-nowrap"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $hoy = date('Y-m-d');
                $arrayPreciosProductos = [];
                $esMayorista = App\Almacene::find(auth()->user()->almacen_id);
            @endphp
            @foreach ($productos as $key => $p)
            @php
                $promosArray = [];
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

                $imagen = App\ImagenesProducto::where('producto_id', $p->id)->first();
                if($imagen){
                    $nombre = $imagen->imagen;
                }else{
                    $nombre = "sinImagen.jpg";
                }

                $precioProducto = App\Precio::where('producto_id', $p->id)->where('escala_id', 1)->first();
                // $cantidadEscala = $precioProducto->escala[]
                $cantidadTotal = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) - SUM(salida) as total'))
                ->where('producto_id', $p->id)
                ->where('almacene_id', auth()->user()->almacen_id)
                ->first();

                // calculamos los paquetes
                /*$detalleEscala = App\Precio::where('producto_id', $p->id)->get();
                $cantitdadCalculada = intval($cantidadTotal->total);
                foreach($detalleEscala as $e)
                {
                    // echo $calculoPaquetes;
                }*/

                // sacamos los precios de los productos
                $preciosProductos = App\Precio::where('producto_id', $p->id)
                                    ->where('precio', '<>',0)
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
                    <td><img src="{{ asset('imagenesProductos')."/".$nombre }}" alt="" height="36"
                        onclick="muestraImagenProducto('{{ $nombre }}')"></td>
                    <td>
                        {{ $p->codigo }}
                        <input type="hidden" id="preciosEscalas_{{ $p->id }}" name="preciosEscalas_{{ $p->id }}" value="{{ $arrayPreciosProductosJson }}">
                        <small id="tags_promos" class="badge badge-default badge-warning form-text text-white" onclick="muestraExistencias({{ $p->id }})">Ver</small>
                    </td>
                    <td>
                        {{ $p->nombre }}
                        @php
                            $contadorPromos = 0;
                        @endphp
                        @forelse ($promosArray as $pA)
                            <small id="tags_promos" class="badge badge-default badge-danger form-text text-white" onclick="muestraPromo({{ $pA }})">P {{ ++$contadorPromos }}</small>
                        @empty
                            
                        @endforelse
                    </td>
                    <td>{{ $p->marca }}</td>
                    <td>{{ $p->tipo }}</td>
                    <td>{{ $p->modelo }}</td>
                    <td>{{ $p->colores }}</td>
                    <td>
{{--                         @foreach ($array as $element)
                            @php
                                $cantidadEscala = $e->escala['minimo'];
                                $calculoPaquetes = intdiv($cantitdadCalculada, $cantidadEscala);
                                $resto = $cantitdadCalculada%$cantidadEscala;
                            @endphp
                        @endforeach
 --}}                        <h3 class="text-info text-right">{{ intval($cantidadTotal->total) }}</h3>
                    </td>
                    <td><h3 class="text-primary text-right">{{ $precioProducto->precio }}</h3></td>
                    <td>
                        @if ($cantidadTotal->total > 0)
                            <button type="button" class="btnSelecciona btn btn-info" data-venta="tienda" title="VENTA POR UNIDADES"><i class="fas fa-plus"></i></button>
                            @if ($esMayorista->mayorista == 'Si')
                                <button type="button" class="btnSeleccionaMayor btn btn-danger" data-venta="mayor" title="VENTA POR MAYOR"><i class="fas fa-plus"></i></button>
                            @endif
                        @endif
                    </td>
                </tr>    
            @endforeach
        </tbody>
    </table>
    @else
        <h3 class="text-danger text-center">No existen registros</h3>
    @endif
</div>
<script>
    $(document).ready(function () {
        // ponemos el evento de hacer click en los botones del listado ajax
        $("#tablaProductosEncontrados").on('click', '.btnSelecciona, .btnSeleccionaMayor', function () {

            $("#listadoProductosAjax").hide('slow'); //ocultamos la tabla para buscar otro item
            $("#termino").val(""); //limpiamos el input de busqueda
            $("#termino").focus(); //posicionamos el foco en el input de busqueda

            let currentRow = $(this).closest("tr"); //agarramos toda la fila de la tabla
            let id      = currentRow.find("td:eq(0)").text();
            let imagen      = currentRow.find("td:eq(1)").html();
            let codigo  = currentRow.find("td:eq(2)").html();
            let nombre  = currentRow.find("td:eq(3)").html();
            let marca   = currentRow.find("td:eq(4)").text();
            let tipo    = currentRow.find("td:eq(5)").text();
            let modelo  = currentRow.find("td:eq(6)").text();
            let colores = currentRow.find("td:eq(7)").text();
            let stock   = currentRow.find("td:eq(8)").html();
            let precio  = currentRow.find("td:eq(9)").text();
            let stockNum = currentRow.find("td:eq(10)").text();

            precios = $("#preciosEscalas_"+id).val(); //capturamos los precios del input
            let tipoVenta = $(this).data('venta');
            // preguntamos si la venta es al mayor o al menor
            if(tipoVenta == 'tienda'){

                let buscaItem = itemsPedidoArray.lastIndexOf(id);
                if(buscaItem < 0)
                {
                    itemsPedidoArray.push(id);
                    t.row.add([
                        imagen,
                        codigo,
                        nombre,
                        marca,
                        stock,
                        `<input type="number" class="form-control text-right cantidad" name="cantidad[`+id+`]" id="cantidad_`+id+`" value="1" data-id="`+id+`" min="1" max="`+stockNum+`" style="width: 70px;">`,
                        `<input type="number" class="form-control text-right precio" name="precio[`+id+`]" id="precio_`+id+`" value="`+precio+`" data-id="`+id+`" step="any" min="1" style="width: 100px;">
                        <input type="hidden" name="precio_venta[`+id+`]" value="`+precio+`">`,
                        `<input type="number" class="form-control text-right subtotal" name="subtotal[`+id+`]" id="subtotal_`+id+`" value="`+precio+`" step="any" style="width: 120px;" readonly>`,
                        '<button type="button" class="btnElimina btn btn-danger" title="Elimina Producto"><i class="fas fa-trash-alt"></i></button>'
                    ]).draw(false);
                    sumaSubTotales();
                    $("#bloqueProductosUnidad").show('slow'); //mostarmos la tabla de ventas al menor
                }                

            }else{

                let buscaItemMayor = itemsPedidoArrayMayor.lastIndexOf(id);
                if(buscaItemMayor < 0)
                {
                    itemsPedidoArrayMayor.push(id);
                    tm.row.add([
                        imagen,
                        codigo,
                        nombre,
                        marca,
                        stock,
                        '<select class="form-control" name="escala_id_m['+id+']" id="escala_m_'+id+'" onchange="cambiaPrecioM('+id+')"></select>',
                        `<input type="number" class="form-control text-right cantidadMayor" name="cantidad_m[`+id+`]" id="cantidad_m_`+id+`" value="1" data-idm="`+id+`" min="1" max="`+stockNum+`" style="width: 70px;">`,
                        `<input type="number" class="form-control text-right precioMayor" name="precio_m[`+id+`]" id="precio_m_`+id+`" value="`+precio+`" data-idm="`+id+`" step="any" min="1" style="width: 100px;">
                        <input type="hidden" name="precio_venta_m[`+id+`]" id="precio_venta_m_`+id+`" value="`+precio+`">
                        <input type="hidden" name="cantidad_escala_m[`+id+`]" id="cantidad_escala_m_`+id+`" value="1">`,
                        `<input type="number" class="form-control text-right subtotalMayor" name="subtotal_m[`+id+`]" id="subtotal_m_`+id+`" value="`+precio+`" step="any" style="width: 120px;" readonly>`,
                        '<button type="button" class="btnEliminaMayor btn btn-danger" title="Elimina Producto"><i class="fas fa-trash-alt"></i></button>'
                    ]).draw(false);
                    sumaSubTotales();
                    adicionaItemUnidad(precios, id);
                    $("#bloqueProductosMayor").show('slow'); //mostarmos la tabla de ventas al mayor
                }                

            }
        });

    });

    // funcion para llenar el combo de los productos al por mayor
    function adicionaItemUnidad(precios, productoId)
    {
        let objetoPrecios = JSON.parse(precios);
        for (let [key, value] of Object.entries(objetoPrecios)) {
            $('#escala_m_'+productoId).append(`<option value="`+value.escala_id+`" data-cantidad="`+value.minimo+`" data-precio="`+value.precio+`">`+value.nombre+`</option>`);
        }
    }

    function cambiaPrecioM(productoId)
    {
        let precio = $("#escala_m_"+productoId).find(':selected').data('precio');
        let cantidadEscala = $("#escala_m_"+productoId).find(':selected').data('cantidad');
        $("#precio_m_"+productoId).val(precio);
        $("#precio_venta_m_"+productoId).val(precio);
        let cantidadMayor = Number($("#cantidad_m_"+productoId).val());
        let precioMayor = Number($("#precio_m_"+productoId).val());
        let subtotalMayor = precioMayor*cantidadMayor;
        $("#subtotal_m_"+productoId).val(subtotalMayor);
        $("#cantidad_escala_m_"+productoId).val(cantidadEscala);
        sumaSubTotales();
    }

</script>