<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style type="text/css">
        @media print {
            #boton_imprimir {
                display: none;
            }
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
        }

        #fondo {
            /* background-image: url("{{ asset('assets/images/factura_szone.jpg') }}"); */
            width: 300px;
            /* height: 514px; */
        }

        #datosFactura {
            text-align: center;
            /* font-size: 10pt; */
        }

        #datosFacturaEmpresa {
            text-align: center;
            font-size: 10pt;
        }

        #qrcode {
            font-weight: bold;
            /* font-size: 10pt; */
        }

        #codigoControl {
            font-weight: bold;
            /* font-size: 10pt; */
        }

        /*estilos para tablas de datos*/
        table.datos {
            /*font-size: 13px;*/
            /*line-height:14px;*/
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        .datos th {
            height: 8px;
            color: #000;
        }

        .datos td {
            height: 12px;
        }

        .datos th,
        .datos td {
            border: 1px solid #ddd;
            padding: 1px;
            text-align: center;
        }

        .datos tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /*fin de estilos para tablas de datos*/
    </style>
    <script src="{{ asset('js/NumeroALetras.js') }}"></script>
    <script src="{{ asset('dist/js/qrcode.min.js') }}"></script>
</head>

<body>
    <div id="fondo">
        <div id="datosFacturaEmpresa">
            <img src="{{ asset('assets/images/logo_bacor.jpg') }}" width="280" />
            <p></p>
            SALIDA ALMACEN

        </div>
        <table class="datos">
            <thead>
                <tr>
                    <th>
                        <h3>CAN</h3>
                    </th>
                    <th>
                        <h3>DETALLE</h3>
                    </th>
                    <th>
                        <h3>P/U</h3>
                    </th>
                    <th>
                        <h3>S/T</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                $sumaSubTotal = 0;
                @endphp
                @foreach ($productosVenta as $con => $pv)
                <tr>
                    <td style="text-align: right;">
                        {{ intval($pv->cantidad) }}
                    </td>

                    <td width="400px" style="text-align: left;">
                        {{ $pv->producto->nombre }}
                        &nbsp;
                        <span><b>{{ ($pv->precio_cobrado_mayor>0)?$pv->escala->nombre:"" }}</b></span>
                        <span><b>{{ ($pv->combo_id != null)?$pv->combo->nombre:"" }}</b></span>
                    </td>

                    @php
                    if ($pv->precio_cobrado_mayor>0) {
                    $precio_costo = $pv->precio_cobrado_mayor;
                    }else{
                    $precio_costo = $pv->precio_cobrado;
                    }
                    $subTotal = $precio_costo * $pv->cantidad;
                    $sumaSubTotal += $subTotal;
                    @endphp
                    <td style="text-align: right;"><b>{{ $precio_costo }}</b></td>
                    <td style="text-align: right;"><b>{{ $subTotal }}</b></td>

                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <h3>TOTAL</h3>
                    </td>
                    <td style="text-align: right;">
                        <h3>{{ $sumaSubTotal }}</h3>
                    </td>
                </tr>
            </tfoot>

        </table>

    </div>
    <input type="button" name="imprimir" id="boton_imprimir" value="Imprimir" onclick="window.print();">

    <script>

		$("#botonImprimir").click(function () {
			var mode = 'iframe'; //popup
			var close = mode == "popup";
			var options = {
				mode: mode,
				popClose: close
			};
			$("div#printableArea").printArea(options);
		});
    </script>

</body>

</html>