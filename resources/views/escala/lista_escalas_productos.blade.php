
<div class="card card-outline-warning">
    <div class="card-header">
        <h4 class="mb-0 text-white">PRODUCTOS PARA PEDIDO</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive m-t-40">
            <table id="example" class="display">
                <div class="button-group">
                    <button type="button" onclick="selecciona_todo()" class="btn waves-effect waves-light btn-success">Seleccionar Todo</button>
                    <button type="button" onclick="quitar_todo()" class="btn waves-effect waves-light btn-secondary">Quitar Todo</button>
                </div>
                <br>
                <thead>
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Modelo</th>
                        <th>Colores</th>
                    </tr>
                </thead>
                <tbody id="todo">
                    @foreach($producto as $prod)
                        <tr>
                            <td><input type="checkbox" class="todo" name="producto_id[]" value="{{ $prod->id }}"></td>
                            <td>{{ $prod->codigo }}</td>
                            <td>{{ $prod->nombre }}</td>
                            <td>{{ $prod->marca->nombre }}</td>
                            <td>{{ $prod->tipo->nombre }}</td>
                            <td>{{ $prod->modelo }}</td>
                            <td>{{ $prod->colores }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Modelo</th>
                        <th>Colores</th>
                    </tr>
                </tfoot>
            </table>
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <button type="submit" class="btn waves-effect waves-light btn-block btn-success">GUARDAR</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function (){
            var table = $('#example').DataTable();
        });

    function selecciona_todo(){
        // alert('si');
        $('.todo').prop("checked", true);
    }

    function quitar_todo(){
        $('.todo').prop("checked", false);
    }

    

</script>
