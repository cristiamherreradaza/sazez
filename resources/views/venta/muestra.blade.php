@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="card card-body">
    <div class="invoice-123" id="printableArea">
        <div class="row pt-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4"><h2><span class="text-info">Venta #</span> {{ $datosVenta->id }}</h2></div>
                    <div class="col-md-4"><h2><span class="text-info">Cliente:</span> {{ $datosVenta->cliente->name }}</h2></div>
                    <div class="col-md-4"><h2><span class="text-info">Fecha: </span> {{ $datosVenta->fecha }}</h2></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive mt-5" style="clear: both;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>MARCA</th>
                                <th>TIPO</th>
                                <th class="text-right"></th>
                                <th class="text-right">PRECIO</th>
                                <th class="text-right">CANTIDAD</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sumaSubTotal = 0;
                            @endphp
                            @foreach ($productosVenta as $con => $pv)
                                <tr>
                                    <td class="text-center">{{ ++$con }}</td>
                                    <td>{{ $pv->producto->codigo }}</td>
                                    <td>{{ $pv->producto->nombre }}</td>
                                    <td>{{ $pv->producto->marca->nombre }}</td>
                                    <td>{{ $pv->producto->tipo->nombre }}</td>
                                    <td class="text-right"><b>{{ ($pv->precio_cobrado_mayor>0)?$pv->escala->nombre:"" }}</b></td>
                                    <td class="text-right">{{ ($pv->precio_cobrado_mayor>0)?$pv->precio_cobrado_mayor:$pv->precio_cobrado }}</td>
                                    <td class="text-right"><b>{{ $pv->cantidad }}</td>
                                    @php
                                        if ($pv->precio_cobrado_mayor>0) {
                                            $precio_costo = $pv->precio_cobrado_mayor;
                                        }else{
                                            $precio_costo = $pv->precio_cobrado;
                                        }
                                        $subTotal = $precio_costo * $pv->cantidad;
                                        $sumaSubTotal += $subTotal;
                                    @endphp
                                    <td class="text-right">{{ $subTotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="pull-right mt-4 text-right">
                    <hr>
                    <h3><b>Total :</b> {{ $sumaSubTotal }}</h3>
                </div>
                <div class="clearfix"></div>
                <hr>
                {{-- <div class="text-right">
                    <button class="btn btn-danger" type="submit"> Proceed to payment </button>
                    <button class="btn btn-default print-page" type="button"> <span><i class="fa fa-print"></i> Print</span>
                    </button>
                </div> --}}
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>

<!--This page JavaScript -->
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
<script>
    $(function () {
        $('#myTable').DataTable({
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });

</script>
<script>
    function nueva_marca()
    {
        $("#modal_marcas").modal('show');
    }

    function guarda_marca()
    {
        var nombre_marca = $("#nombre_marca").val();
        if(nombre_marca.length>0){
            Swal.fire(
                'Excelente!',
                'Una nueva marca fue registrada.',
                'success'
            )
        }
    }

    function editar(id, nombre)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#editar_marcas").modal('show');
    }

    function actualiza_marca()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        if(nombre.length>0){
            Swal.fire(
                'Excelente!',
                'Marca actualizada correctamente.',
                'success'
            )
        }
    }

    function eliminar(id, nombre)
    {
        Swal.fire({
            title: 'Quieres borrar ' + nombre + '?',
            text: "Luego no podras recuperarlo!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, estoy seguro!',
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Excelente!',
                    'La marca fue eliminada',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Marca/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection