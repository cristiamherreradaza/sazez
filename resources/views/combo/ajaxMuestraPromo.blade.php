<div>{{ $datosCombo->nombre }}</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">CODIGO</th>
                <th scope="col">NOMBRE</th>
                <th scope="col">PRECIO</th>
                <th scope="col">CANTIDAD</th>
                <th scope="col">PROMOCION</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itemsCombo as $i)
            <tr>
                <th scope="row">{{ $i->producto->codigo }}</th>
                <td>{{ $i->producto->nombre }}</td>
                @php
                    $precioProducto = App\Precio::where('producto_id', $i->producto->id)
                                ->where('escala_id', 1)
                                ->first();
                @endphp
                <td class="text-right">{{ $precioProducto->precio }}</td>
                <td class="text-center">{{ $i->cantidad }}</td>
                <td class="text-info text-right">{{ $i->precio }}</td>
            </tr>    
            @endforeach
        </tbody>
    </table>
</div>