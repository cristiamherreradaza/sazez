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
                        <th>$. Tienda</th>
                        <th>Otros $.</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $key => $producto)
                    @php
                        $imagen = App\ImagenesProducto::where('producto_id', $producto->id)->first();

                        $precioTienda = App\Precio::where('producto_id', $producto->id)
                                                    ->where('escala_id', 1)
                                                    ->first();

                        $otrosPrecios = App\Precio::where('producto_id', $producto->id)
                                                    ->where('escala_id', '<>',  1)
                                                    ->get();

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
                            <td>{{ $precioTienda->precio }}</td>
                            <td>
                                @forelse ($otrosPrecios as $op)
                                    {{ $op->escala->nombre }}: {{ $op->precio}} <br />
                                @empty
                                    <p></p>
                                @endforelse
                            </td>
                            <td>
                                <button class="btn btn-info" onclick="muestra_producto('{{ $producto->id }}')" title="Ver producto"><i class="fas fa-eye"></i></button>
                                
                                @php
                                    $movimiento = App\Movimiento::where('producto_id', $producto->id)
                                                                ->groupBy('producto_id')
                                                                ->count();
                                @endphp

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