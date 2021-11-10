<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>id</th>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Marca</th>
                {{-- <th>Saldo Total</th> --}}
                <th>Escala 1</th>
                <th>Escala 2</th>
                <th>Escala 3</th>
                <th>Escala 4</th>
                <th>Escala 5</th>
                <th>Escala 6</th>
            </tr>
        </thead>
        <tbody>
            {{-- @dd($productos) --}}
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->tipo->nombre }}</td>
                    <td>{{ $producto->marca->nombre }}</td>
                    {{-- @php
                        $saldo = App\Movimiento::select(DB::raw("(SUM(ingreso) - SUM(salida)) as total"))
                                                        ->whereNull('deleted_at')
                                                        ->where('producto_id', $producto->id)
                                                        ->where('almacene_id', $almacen->id)
                                                        ->whereDate('fecha', '<=', $fecha)
                                                        ->get();
                        if($saldo[0]->total)
                        {
                            $saldo = round($saldo[0]->total);
                        }
                        else
                        {
                            $saldo = 0;
                        }
                    @endphp
                    <td>{{ $saldo }}</td> --}}
                    @php
                        $precio = App\Precio::where('producto_id', $producto->id)
                                            ->get();
                                            // dd($precio);
                        $precio1=0;
                        $precio2=0;
                        $precio3=0;
                        $precio4=0;
                        $precio5=0;
                        $precio6=0;
                        foreach($precio as $p){

                            switch ($p->escala_id){
                                case 1 :
                                    $precio1=$p->precio;
                                    // echo "<td>$p->precio</td>";
                                    break;
                                case 2 :
                                    $precio2=$p->precio;

                                    // echo "<td>$p->precio</td>";
                                    break;
                                case 3 :
                                    $precio3=$p->precio;
                                    // echo "<td>$p->precio</td>";
                                    break;
                                case 4 :
                                    $precio4=$p->precio;
                                    // echo "<td>$p->precio</td>";
                                    break;
                                case 5 :
                                    $precio5=$p->precio;
                                    // echo "<td>$p->precio</td>";
                                    break;
                                case 6 :
                                    $precio6=$p->precio;
                                    // echo "<td>$p->precio</td>";
                                    break;
                                default:
                                    // echo "<td></td>";
                            }
                            // if($p->escala_id == 1){
                            //     echo "<td>$p->precio</td>";
                            // }else{
                            //     echo "<td></td>";
                            // }
                            // if($p->escala_id == 3){
                            //     echo "<td>$p->precio</td>";
                            // }else{
                            //     echo "<td></td>";
                            // }
                            // if($p->escala_id == 4){
                            //     echo "<td>$p->precio</td>";
                            // }else{
                            //     echo "<td></td>";
                            // }
                            // if($p->escala_id == 5){
                            //     echo "<td>$p->precio</td>";
                            // }else{
                            //     echo "<td></td>";
                            // }
                            // if($p->escala_id == 6){
                            //     echo "<td>$p->precio</td>";
                            // }else{
                            //     echo "<td></td>";
                            // }
                            // if($p->escala_id == 8){
                            //     echo "<td>$p->precio</td>";
                            // }else{
                            //     echo "<td></td>";
                            // }
                        }
                    @endphp
                    <td>{{ $precio1 }}</td>
                    <td>{{ $precio2 }}</td>
                    <td>{{ $precio3 }}</td>
                    <td>{{ $precio4 }}</td>
                    <td>{{ $precio5 }}</td>
                    <td>{{ $precio6 }}</td>
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