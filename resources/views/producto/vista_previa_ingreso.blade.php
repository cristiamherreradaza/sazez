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
        .datos th, .datos td {
          border: 1px solid #ddd;
          padding: 2px;
          text-align: center;
        }
        .datos tr:nth-child(even) {background-color: #f2f2f2;}
        /*fin de estilos para tablas de datos*/
        /*estilos para tablas de contenidos*/
        table.contenidos {
            /*font-size: 13px;*/
            line-height:10px;
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
        .contenidos th, .contenidos td {
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
            width: 813px;
        }
        .information {
            background-color: #60A7A6;
            color: #FFF;
            line-height:7px;
        }
        .information .logo {
            margin: 5px;
        }
        .information table {
            padding: 10px;
        }
        .glosa {
            font-size: 10px;
            line-height:14px;
        }
        .pie_pagina {
            font-size: 10px;
            line-height:14px;
        }
        .titulo {
            font-size: 13px;
            line-height:18px;    
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
    <p style="margin-top: 5px; font-size: 15px; text-align: center;">RECIBO DE INGRESO DE PRODUCTOS</p>
    <table class="contenidos">
        <tr>
            <td><b>Numero de Ingreso :</b> {{ $detalle->numero_ingreso }} </td>
            <td><b>Fecha :</b> {{ $detalle->fecha }}</td>
        </tr>
        <tr>
            <td><b>Proveedor :</b> 
                @if($detalle->proveedor)
                    {{ $detalle->proveedor->nombre }}
                @else
                    No Tiene
                @endif
            </td>
            <td><b>Almacen :</b> {{ $detalle->almacen->nombre }} </td>
        </tr>
    </table>
    <!-- Detalle de los Productos -->
    <br>
    <div class="titulo">Detalle de Productos</div>
    @php
        /*$demo1 = intdiv(19,5);
        $demo2 = 19%5;

        echo $demo1."<br>";
        echo $demo2;*/
    @endphp
    <table class="datos">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-right">Codigo</th>
                <th class="text-right">Nombre</th>
                <th class="text-right">Tipo</th>
                <th class="text-right">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos_envio as $key => $producto)
            @php
                // $detalleEscala = App\Precio::where('producto_id', $producto->producto->id)->get();
                // dd($detalleEscala);
            @endphp
                <tr>
                    <td>{{ ($key+1) }}</td>
                    <td style="text-align: left;">{{ $producto->producto->codigo }}</td>
                    <td style="text-align: left;">{{ $producto->producto->nombre }}</td>
                    <td style="text-align: left;">{{ $producto->producto->tipo->nombre }}</td>
                    <td style="text-align: right;"><b>{{ round($producto->ingreso) }}</b></td>
                </tr>
            @endforeach
            @if($complemento > 0)
                @for($i=1; $i<=$complemento; $i++)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            @endif
        </tbody>
    </table>
    <table class="firmas">
        <tr>
            <td><hr style="width: 150px"> Entregue Conforme</td>
            <td><hr style="width: 150px"> Recibi Conforme</td>
        </tr>
    </table>
</div>
@if($cantidad_producto <= 20)
<br>
<br>
<div class="invoice" id="printableArea">
    <p style="margin-top: 5px; font-size: 15px; text-align: center;">RECIBO DE INGRESO DE PRODUCTOS</p>
    <table class="contenidos">
        <tr>
            <td><b>Numero de Ingreso :</b> {{ $detalle->numero_ingreso }} </td>
            <td><b>Fecha :</b> {{ $detalle->fecha }}</td>
        </tr>
        <tr>
            <td><b>Proveedor :</b> 
                @if($detalle->proveedor)
                    {{ $detalle->proveedor->nombre }}
                @else
                    No Tiene
                @endif
            </td>
            <td><b>Almacen :</b> {{ $detalle->almacen->nombre }} </td>
        </tr>
    </table>
    <!-- Detalle de los Productos -->
    <br>
    <div class="titulo">Detalle de Productos</div>
    <table class="datos">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-right">Codigo</th>
                <th class="text-right">Nombre</th>
                <th class="text-right">Tipo</th>
                <th class="text-right">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos_envio as $key => $producto)
                <tr>
                    <td>{{ ($key+1) }}</td>
                    <td style="text-align: left;">{{ $producto->producto->codigo }}</td>
                    <td style="text-align: left;">{{ $producto->producto->nombre }}</td>
                    <td style="text-align: left;">{{ $producto->producto->tipo->nombre }}</td>
                    <td style="text-align: right;"><b>{{ round($producto->ingreso) }}</b></td>
                </tr>
            @endforeach
            @if($complemento > 0)
                @for($i=1; $i<=$complemento; $i++)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            @endif
        </tbody>
    </table>
    <table class="firmas">
        <tr>
            <td><hr style="width: 150px"> Entregue Conforme</td>
            <td><hr style="width: 150px"> Recibi Conforme</td>
        </tr>
    </table>
</div>
@endif
<input type="button" name="imprimir" id="boton_imprimir" value="Imprimir" onclick="window.print();">
<!-- <button id="botonImprimir" class="btn btn-inverse btn-block print-page" type="button"> <span><i class="fa fa-print"></i> IMPRIMIR </span></button> -->

<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
<script>
    $("#botonImprimir").click(function() {
        var mode = 'iframe'; //popup
        var close = mode == "popup";
        var options = {
                mode: mode,
                popClose: close
        };
        $("div#printableArea").printArea(options);
    });
</script>