<div class="card card-outline-primary">                                
    <div class="card-header">
        <h4 class="mb-0 text-white">PRODUCTOS EN PEDIDO</h4>
    </div>
    <br />  
    <div class="table-responsive m-t-40">
        @if (!empty($productos_pedido))
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Nombre venta</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos_pedido as $producto)
                        <tr>
                            <td>{{ $producto->producto->nombre }}</td>
                            <td>{{ $producto->producto->nombre_venta }}</td>
                            <td><input style="text-align: center;" size="8" min="0" pattern="^[0-9]+" onchange="calcula( {{ $producto->id }} )" type="number" id="cantidad-{{ $producto->id }}" name="cantidad-{{ $producto->id }}" value="{{ $producto->cantidad }}"></td>
                            <td>
                                <button type="button" class="btn btn-danger" onclick="eliminar_pedido_producto('{{ $producto->pedido_id }}', '{{ $producto->producto_id }}')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
        <p></p>
            <h3>El pedido no tiene productos</h3>
        @endif
    </div>
</div>

<script>
    $(function () {
        $('#myTable').DataTable();
    });

    // function eliminar_combo_producto(combo_id, producto_id)
    // {
    //     $.post("{{ url('Combo/eliminar_combo_producto') }}",
    //     {
    //         combo_id: combo_id,
    //         producto_id: producto_id
    //     });
    //     e.preventDefault();
        
    // }    

    // function eliminar_combo_producto(combo_id, producto_id)
    // {
    //     // $.post("{{ url('Combo/eliminar_combo_producto') }}",
    //     // {
    //     //     combo_id: combo_id,
    //     //     producto_id: producto_id
    //     // });
    //     e.preventDefault();
    //     $.ajax({
    //         url: "{{ url('Combo/eliminar_combo_producto') }}",
    //         method: "POST",
    //         data: {
    //             combo_id : combo_id,
    //             producto_id : producto_id
    //         },
    //         cache: false,
    //         success: function(data)
    //         {
    //             $("#productos_en_combo").load("{{ url('Combo/lista_combo_productos') }}/"+combo_id);
    //         }
    //     })
    // } 
</script>