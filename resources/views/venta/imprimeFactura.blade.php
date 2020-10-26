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
		body{
			font-family: Arial, Helvetica, sans-serif;
			font-size: 9px;
		}
		#fondo{
			/* background-image: url("{{ asset('assets/images/factura_szone.jpg') }}"); */
			width: 300px;
			/* height: 514px; */
		}
		#datosFactura{
			text-align: center;
			/* font-size: 10pt; */
		}

		#datosFacturaEmpresa{
			text-align: center;
			font-size: 10pt;
		}
		#qrcode{
			font-weight: bold;
			/* font-size: 10pt; */
		}
		#codigoControl{
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
			La Paz Bolivia <br />
			ORIGINAL <br />
			----------------------------------- <br />
			NIT: {{ $datosEmpresa->nit }} <br />
			Factura Nro: {{ $datosFactura->numero_factura }} <br />
			AUTORIZACION Nro: {{ $datosFactura->numero_autorizacion }} <br />
			----------------------------------- <br />
			FECHA: {{ $datosVenta->fecha }} <br />
			SE&ntilde;OR(ES): {{ $datosVenta->cliente->razon_social }} <br />
			NIT: {{ $datosFactura->nit_cliente }} <br />
			----------------------------------- <br />
		</div>
		<table class="datos">
			<thead>
			    <tr>
			        <th><h3>CAN</h3></th>
			        <th><h3>DETALLE</h3></th>
			        <th><h3>P/U</h3></th>
			        <th><h3>S/T</h3></th>
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
					<td><h3>TOTAL</h3></td>
					<td style="text-align: right;"><h3>{{ $sumaSubTotal }}</h3></td>
				</tr>
			</tfoot>
			
		</table>
	
		Son: <span id="literalTotal"></span> 000/100 Bs.
		<div id="codigoControl">CODIGO CONTROL: {{ $datosFactura->codigo_control }}</div>
		<br>
		<center>
		<div id="qrcode"></div>
		</center>
		<br>

		<div id="datosFactura">
			ESTA FACTURA CONTRIBUYE AL DESAROLLO DEL PAIS, EL USO ILICITO DE ESTA SERA SANCIONADO DE ACUERDO A LEY
			<br><br>
			Ley 453 los servicios deben suministrarse en condiciones de inocuidad calidad y seguridad
			<br><br>
		</div>
		<br>
	</div>
	<input type="button" name="imprimir" id="boton_imprimir" value="Imprimir" onclick="window.print();">

	<script>
		let valorTotal = Number({{ $sumaSubTotal }});
		var options = { year: 'numeric', month: 'long', day: 'numeric' };
		// var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

		function numerosALetras() {

			// generamos los numeros a letras
		    valorLiteral = numeroALetras(valorTotal, {
		        plural: ' ',
		        singular: ' ',
		        centPlural: ' ',
		        centSingular: ' '
		    });
			// console.log(valorLiteral);
			document.getElementById("literalTotal").innerHTML = valorLiteral;

			// cambiamos la fecha para mejor lectura
			let fecha = new Date("{{ $datosVenta->fecha }}");
			document.getElementById("lugarFecha").innerHTML = "La Paz, " + fecha.toLocaleDateString("es-ES", options);
		}

		window.onload = numerosALetras;

		var qrcode = new QRCode("qrcode", {
			text: "http://jindo.dev.naver.com/collie",
			width: 98,
			height: 90,
			colorDark : "#000000",
			colorLight : "#ffffff",
			correctLevel : QRCode.CorrectLevel.H
		});

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