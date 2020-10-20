<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Impresion Recibo</title>
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->
    <style type="text/css">
        @media print {
            #boton_imprimir {
                display: none;
            }
        }

        @page {
            margin: 15px;
        }

        body {
            background-repeat: no-repeat;
            font-size: 8px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
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
            height: 10px;
            background-color: #616362;
            color: #fff;
        }

        .datos td {
            height: 12px;
        }

        .datos th,
        .datos td {
            border: 1px solid #ddd;
            padding: 2px;
            text-align: center;
        }

        .datos tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /*fin de estilos para tablas de datos*/
        /*estilos para tablas de contenidos*/
        table.contenidos {
            /*font-size: 13px;*/
            line-height: 10px;
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        .contenidos th {
            height: 20px;
            background-color: #616362;
            color: #fff;
        }

        .contenidos td {
            height: 10px;
        }

        .contenidos th,
        .contenidos td {
            border-bottom: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }

        /*.contenidos tr:nth-child(even) {background-color: #f2f2f2;}*/
        /*fin de estilos para tablas de contenidos*/
        .titulo {
            font-weight: bolder;
        }

        .invoice {
            margin-left: 15px;
            width: 400px;
        }

        .information {
            background-color: #60A7A6;
            color: #FFF;
            line-height: 7px;
        }

        .information .logo {
            margin: 5px;
        }

        .information table {
            padding: 10px;
        }

        .glosa {
            font-size: 10px;
            line-height: 14px;
        }

        .pie_pagina {
            font-size: 10px;
            line-height: 14px;
        }

        .titulo {
            font-size: 13px;
            line-height: 18px;
        }

        .firmas td {
            padding-top: 30px;
            text-align: center;
        }

        .firmas {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="invoice" id="printableArea">
        <p style="margin-top: 5px; font-size: 15px; text-align: center;">SALIDA ALMACEN</p>
        <table class="contenidos">
            <tr>
                <td><b>Venta :</b> {{ $datosVenta->id }} </td>
                <td><b>Fecha :</b> {{ $datosVenta->fecha }}</td>
            </tr>
        </table>
        <!-- Detalle de los Productos -->
        <br>
        <table class="datos">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>CODIGO</th>
                    <th>NOMBRE</th>
                    <th class="text-right">CANTIDAD</th>
                    <th class="text-right">PRECIO</th>
                    <th class="text-right">IMPORTE</th>
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
                    <td style="text-align:right">
                        <span class="text-info"><b>{{ ($pv->precio_cobrado_mayor>0)?$pv->escala->nombre:"" }}</b></span>
                        <span class="text-success"><b>{{ ($pv->combo_id != null)?$pv->combo->nombre:"" }}</b></span>
                        &nbsp;&nbsp;&nbsp; <b>{{ intval($pv->cantidad) }}</td>
                    <td style="text-align:right">
                        {{ ($pv->precio_cobrado_mayor>0)?$pv->precio_cobrado_mayor:$pv->precio_cobrado }}
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
                    <td style="text-align:right"><b>{{ $subTotal }}</b></td>
                    
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center"></th>
                    <th></th>
                    <th></th>
                    <th class="text-right"></th>
                    <th class="text-right">TOTAL</th>
                    <th style="text-align:right">{{ $sumaSubTotal }}</th>
                </tr>
            </tfoot>
        </table>
        
        <table class="firmas">
            <tr>
                <td>
                    <hr style="width: 150px"> Entregue Conforme</td>
                <td>
                    <hr style="width: 150px"> Recibi Conforme</td>
            </tr>
        </table>
    </div>
</body>
<input type="button" name="imprimir" id="boton_imprimir" value="Imprimir" onclick="window.print();">
<!-- <button id="botonImprimir" class="btn btn-inverse btn-block print-page" type="button"> <span><i class="fa fa-print"></i> IMPRIMIR </span></button> -->

<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
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