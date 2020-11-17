<div class="card border-primary">
    <div class="card-header bg-primary">
        <h4 class="mb-0 text-white">
            LISTADO DE PRODUCTOS
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Cantidad</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $key => $producto)
                    @php
                        $imagen = App\ImagenesProducto::where('producto_id', $producto->id)->first();
                        if($imagen){
                            $nombre = $imagen->imagen;
                        }else{
                            $nombre = "sinImagen.jpg";
                        }
                    @endphp
                        <tr>
                            <td><img src="{{ asset('imagenesProductos')."/".$nombre }}" alt="" height="36" onclick="muestraImagenProducto('{{ $nombre }}')"></td>
                            <td>{{ $producto->codigo }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->tipo->nombre }}</td>
                            <td>{{ $producto->marca->nombre }}</td>
                            @php
                                if($estado == 'Defectuoso'){
                                    $salida = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(salida) as total'))
                                                            ->where('producto_id', $producto->id)
                                                            ->where('almacene_id', auth()->user()->almacen->id)
                                                            ->where('estado', 'Defectuoso')
                                                            ->first();
                                    $ingreso = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) as total'))
                                                            ->where('producto_id', $producto->id)
                                                            ->where('almacene_id', auth()->user()->almacen->id)
                                                            ->where('estado', 'Reacondicionado')
                                                            ->first();
                                    if($ingreso){
                                        $ingreso = $ingreso->total;
                                    }else{
                                        $ingreso = 0;
                                    }
                                    $resultado = $salida->total - $ingreso;
                                }else{
                                    $ingreso = App\Movimiento::where('producto_id', $producto->id)
                                                            ->where('almacene_id', auth()->user()->almacen->id)
                                                            ->sum('ingreso');
                                    $salida = App\Movimiento::where('producto_id', $producto->id)
                                                            ->where('almacene_id', auth()->user()->almacen->id)
                                                            ->sum('salida');                                                            
                                    $resultado = $ingreso - $salida;
                                }
                            @endphp
                            <td>{{ intval($resultado) }}</td>
                            <td>
                                <button class="btn btn-warning" onclick="edita_producto('{{ $producto->id }}')" title="Editar producto"><i class="fas fa-edit"></i> </button>
                                <button class="btn btn-info" onclick="muestra_producto('{{ $producto->id }}')" title="Ver producto"><i class="fas fa-eye"></i></button>
                                
                                @if (auth()->user()->perfil_id == 1)
                                <button class="btn btn-danger" onclick="quita_producto('{{ $producto->id }}')" title="Quitar producto"><i class="fas fa-minus-circle"></i></button>
                                <button class="btn btn-success" onclick="adiciona_producto('{{ $producto->id }}')" title="Adicionar producto"><i class="fas fa-plus-circle"></i></button>
                                @endif
                                <button class="btn btn-dark" onclick="genera_qr('{{ $producto->id }}')" title="Ver producto"><i class="fas fa-qrcode"></i></button>
                                
                                @if($producto->estado == 'Discontinuo')
                                    <button class="btn btn-primary" onclick="continua_producto('{{ $producto->id }}', '{{ $producto->nombre }}')" title="Continuar producto"><i class="fas fa-check-circle"></i></button>
                                @else
                                    <button class="btn btn-primary" onclick="discontinua_producto('{{ $producto->id }}', '{{ $producto->nombre }}')" title="Discontinuar producto"><i class="fas fa-ban"></i></button>
                                @endif
                                @php
                                    $movimiento = App\Movimiento::where('producto_id', $producto->id)
                                                                ->groupBy('producto_id')
                                                                ->count();
                                @endphp
                                @if($movimiento <= 1)
                                    <button class="btn btn-danger" onclick="elimina_producto('{{ $producto->id }}', '{{ $producto->codigo }}')" title="Eliminar producto"><i class="fas fa-trash-alt"></i></button>
                                @endif
                                @if($estado == 'Defectuoso')
                                    @if($resultado > 0)
                                        <button class="btn btn-success" onclick="habilita_producto('{{ $producto->id }}', '{{ $producto->nombre }}')" title="Habilitar producto"><i class="fas fa-sort-amount-up"></i></button>
                                    @endif
                                @else
                                    @if($resultado > 0)
                                        <button class="btn btn-dark" onclick="reporta_producto('{{ $producto->id }}', '{{ $producto->nombre }}')" title="Reportar producto"><i class="fas fa-sort-amount-down"></i></button>
                                    @endif
                                @endif
                                <button class="btn waves-effect waves-light btn-light" onclick="muestraInformacion('{{ $producto->id }}')" title="Muestra Informacion"><i class="fas fa-th-list"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#tabla-usuarios').DataTable({
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });

</script>