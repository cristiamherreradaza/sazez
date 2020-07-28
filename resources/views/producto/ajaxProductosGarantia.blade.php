
<div class="card card-outline-warning">
    <div class="card-header bg-warning">
        <h4 class="mb-0 text-white">PRODUCTOS</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive m-t-40">
            <table id="config-table" class="table table-bordered table-striped">
                <div class="button-group">
                    <button type="button" onclick="selecciona_todo()" class="btn waves-effect waves-light btn-success">Seleccionar Todo</button>
                    <button type="button" onclick="quitar_todo()" class="btn waves-effect waves-light btn-secondary">Quitar Todo</button>
                </div>
                <br>
                <thead>
                    <tr>
                        <td style="width: 5%">ID</td>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Modelo</th>
                        <th>Colores</th>
                    </tr>
                </thead>
                <tbody id="todo">
                    @foreach($productos as $producto)
                        <tr>
                            <td><input type="checkbox" class="todo" name="producto_id[]" value="{{ $producto->id }}"></td>
                            <td>{{ $producto->codigo }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->marca->nombre }}</td>
                            <td>{{ $producto->tipo->nombre }}</td>
                            <td>{{ $producto->modelo }}</td>
                            <td>{{ $producto->colores }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
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
    $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $("#config-table thead th").each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder=" ' + title + '" />');
        });

        // Configuraciones del datatable
        var table = $("#config-table").DataTable({
            responsive: true,
            "order": [
                [0, 'asc']
            ],
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });

        // Funcion para aplicar el filtrado de columnas en el datatable
        table.columns().every(function(index) {
        var that = this;
        $("input", this.header()).on("keyup change clear", function() {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
                table
                .rows()
                .$("tr", { filter: "applied" })
                .each(function() {
                    console.log(table.row(this).data());
                });
            }
            });
        });
    });

    // Funcion para seleccionar todos los items en el datatable
    function selecciona_todo(){
        // alert('si');
        $('.todo').prop("checked", true);
    }

    // Funcion para quitar todos los items seleccionados en el datatable
    function quitar_todo(){
        $('.todo').prop("checked", false);
    }
</script>
