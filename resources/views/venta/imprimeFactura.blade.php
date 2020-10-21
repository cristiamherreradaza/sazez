<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Factura</title>
	<style type="text/css">
		body{
				font-family: Arial, Helvetica, sans-serif;
				}
		#fondo{
			/* background-image: url("{{ asset('assets/images/factura_szone.jpg') }}"); */
			width: 302px;
			/* height: 514px; */
		}

		#tablaProductos{
			font-size: 8pt;
			/* position: absolute;
			top: 260px;
			left: 50px; */
		}

		#datosFactura{
			text-align: center;
			font-size: 10pt;
		}

		#literalTotal{
			font-weight: bold;
			font-size: 10pt;
		}

		#qrcode{
			font-weight: bold;
			font-size: 10pt;
		}

		#codigoControl{
			font-weight: bold;
			font-size: 10pt;
		}


	</style>
	<script src="{{ asset('js/NumeroALetras.js') }}"></script>
	<script src="{{ asset('dist/js/qrcode.min.js') }}"></script>
</head>
<body>
	<div id="fondo">
		<div id="datosFactura">
			<img src="{{ asset('assets/images/logo_bacor.jpg') }}" width="280" />

			NIT: {{ $datosEmpresa->nit }} <br />
			Factura No: {{ $datosFactura->numero_factura }} <br />
			Factura No: {{ $datosFactura->numero_autorizacion }} <br />
			<h1></h1>
			<hr>
			Fecha: {{ $datosVenta->fecha }} <br />
			Se&ntilde;or(es): {{ $datosVenta->cliente->razon_social }} <br />
			Nit: {{ $datosFactura->nit_cliente }}
		</div>
		<h1></h1>
		<table id="tablaProductos">
			<thead>
			    <tr>
			        <th>DETALLE</th>
			        <th>CANTIDAD</th>
			        <th>IMPORTE</th>
			    </tr>
			</thead>
			<tbody>
				@php
					$sumaSubTotal = 0;
				@endphp
				@foreach ($productosVenta as $con => $pv)
					<tr>
						<td width="400px">{{ $pv->producto->nombre }}</td>
						<td style="text-align: right;">
							<span><b>{{ ($pv->precio_cobrado_mayor>0)?$pv->escala->nombre:"" }}</b></span>
							<span><b>{{ ($pv->combo_id != null)?$pv->combo->nombre:"" }}</b></span>
							&nbsp;&nbsp; <b>{{ intval($pv->cantidad) }}</td>
						
						@php
							if ($pv->precio_cobrado_mayor>0) {
								$precio_costo = $pv->precio_cobrado_mayor;
							}else{
								$precio_costo = $pv->precio_cobrado;
							}
							$subTotal = $precio_costo * $pv->cantidad;
							$sumaSubTotal += $subTotal;
						@endphp
						<td style="text-align: right;"><b>{{ $subTotal }}</b></td>
						
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td>TOTAL</td>
					<td style="text-align: right;">{{ $sumaSubTotal }}</td>
				</tr>
			</tfoot>
			
		</table>
	
		<div id="literalTotal"></div>
		<div id="codigoControl">CODIGO CONTROL: {{ $datosFactura->codigo_control }}</div>
		<h1></h1>
		<center>
		<div id="qrcode"></div>
		</center>
		<h1></h1>

		<div id="datosFactura">
			ESTA FACTURA CONTRIBUYE AL DESAROLLO DEL PAIS, EL USO ILICITO DE ESTA SERA SANCIONADO DE ACUERDO A LEY
		</div>
	</div>

	<script>
		let valorTotal = Number({{ $sumaSubTotal }});
		var options = { year: 'numeric', month: 'long', day: 'numeric' };
		// var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

		function numerosALetras() {

			// generamos los numeros a letras
		    valorLiteral = numeroALetras(valorTotal, {
		        plural: 'BOLIVIANOS',
		        singular: 'Bolivianos',
		        centPlural: 'Centavos',
		        centSingular: 'Centavo'
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
	</script>
	
</body>
</html>