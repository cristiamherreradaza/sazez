<h3>
    {{ $datosCombo->nombre }}
    Desde: <span class="text-info">{{ $datosCombo->fecha_inicio }}</span>
    Hasta: <span class="text-info">{{ $datosCombo->fecha_final }}</span>
</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">CODIGO</th>
                <th scope="col">NOMBRE</th>
                <th scope="col">CANTIDAD</th>
                <th scope="col">PRECIO</th>
                <th scope="col">PROMOCION</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($itemsCombo as $i)
            <tr>
                <th scope="row">{{ $i->producto->codigo }}</th>
                <td>{{ $i->producto->nombre }}</td>
                <td class="text-center">{{ $i->cantidad }}</td>
                @php
                    $precioProducto = App\Precio::where('producto_id', $i->producto->id)
                                ->where('escala_id', 1)
                                ->first();
                    $total += $i->precio;
                @endphp
                <td class="text-right">{{ $precioProducto->precio }}</td>
                <td class="text-info text-right">{{ $i->precio }}</td>
            </tr>    
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col">TOTAL</th>
                <th scope="col" class="text-right">{{ $total }}</th>
            </tr>
        </tfoot>
    </table>
</div>
<a class="btn waves-effect waves-light text-white btn-block btn-info" onclick="adicionaPromocion('{{ $datosCombo->id }}', '{{ $datosCombo->nombre }}', '{{ $total }}')">ADICIONA PROMOCION</a>