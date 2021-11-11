<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                {{-- <th>id</th> --}}
                <th>Codigo</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Marca</th>
                @php
                    $escalas = App\Escala::all();

                    foreach ($escalas as $key => $e) {
                        echo "<th> ". $e->nombre ."</th>";
                    }
                @endphp
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    {{-- <td>{{ $producto->id }}</td> --}}
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->tipo->nombre }}</td>
                    <td>{{ $producto->marca->nombre }}</td>
                    @php
                        $escalas = App\Escala::all();
                    @endphp
                    @foreach ($escalas as $e)
                        @php
                            $precio = App\Precio::where('producto_id', $producto->id)
                                                ->where('escala_id',$e->id)
                                                ->first();
                        @endphp
                        <td>{{ ($precio)? $precio->precio : '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(function () {
        $('#tabla-usuarios').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });
</script>