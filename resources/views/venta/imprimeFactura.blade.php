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
			background-image: url("{{ asset('assets/images/factura_szone.jpg') }}");
			width: 792px;
			height: 514px;
		}

		#tablaProductos{
			font-size: 8pt;
			position: absolute;
			top: 260px;
			left: 50px;
		}

		#total{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 402px;
			left: 720px;
		}

		#literalTotal{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 402px;
			left: 90px;
		}

		#qrcode{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 420px;
			left: 660px;
		}

		#codigoControl{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 425px;
			left: 180px;
		}

		#fechaLimite{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 450px;
			left: 217px;
		}

		#nitEmpresa{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 122px;
			left: 358px;
		}

		#numeroFactura{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 140px;
			left: 420px;
		}

		#numeroAutorizacion{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 158px;
			left: 430px;
		}
		#lugarFecha{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 193px;
			left: 155px;
		}
		#razonCliente{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 213px;
			left: 125px;
		}

		#nitCliente{
			font-weight: bold;
			font-size: 10pt;
			position: absolute;
			top: 193px;
			left: 480px;
		}

	</style>
	<script src="{{ asset('js/NumeroALetras.js') }}"></script>
	<script src="{{ asset('dist/js/qrcode.min.js') }}"></script>
</head>
<body>
	<div id="fondo">

		<table id="tablaProductos">
			
			<tbody>
				@php
					$sumaSubTotal = 0;
				@endphp
				@foreach ($productosVenta as $con => $pv)
					<tr>
						<td width="65px">&nbsp;&nbsp;
							{{ str_pad($pv->producto->id, 5, "0", STR_PAD_LEFT) }}
						</td>
						<td width="425px">{{ $pv->producto->nombre }}</td>
						<td style="text-align: right;" width="100px">
							<span class="text-info"><b>{{ ($pv->precio_cobrado_mayor>0)?$pv->escala->nombre:"" }}</b></span>
							<span class="text-success"><b>{{ ($pv->combo_id != null)?$pv->combo->nombre:"" }}</b></span>
							&nbsp;&nbsp;&nbsp; <b>{{ intval($pv->cantidad) }}</td>
						
						@php
							if ($pv->precio_cobrado_mayor>0) {
								$precio_costo = $pv->precio_cobrado_mayor;
							}else{
								$precio_costo = $pv->precio_cobrado;
							}
							$subTotal = $precio_costo * $pv->cantidad;
							$sumaSubTotal += $subTotal;
						@endphp
						<td style="text-align: right;" width="100px"><b>{{ $subTotal }}</b></td>
						
					</tr>
				@endforeach
			</tbody>
			
		</table>
		<div id="total">
			{{ $sumaSubTotal }}
		</div>
		<div id="literalTotal"></div>
		<div id="codigoControl">{{ $datosFactura->codigo_control }}</div>
		<div id="fechaLimite">{{ $datosFactura->fecha_limite }}</div>
		<div id="nitEmpresa">{{ $datosEmpresa->nit }}</div>
		<div id="numeroFactura">{{ $datosFactura->numero_factura }}</div>
		<div id="numeroAutorizacion">{{ $datosFactura->numero_autorizacion }}</div>
		<div id="lugarFecha">La Paz, {{ $datosVenta->fecha }}</div>
		<div id="razonCliente">{{ $datosVenta->cliente->razon_social }}</div>
		<div id="nitCliente">{{ $datosFactura->nit_cliente }}</div>
		<div id="qrcode"></div>
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