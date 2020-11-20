<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Factura</title>
	<style type="text/css">
		@media print {
			#btnImprimir {
				display: none;
			}
		}

		#botonImpresion {
			background: #17aa56;
			color: #fff;
			border-radius: 7px;
			/*box-shadow: 0 5px #119e4d;*/
			padding: 15px;
		}

		#botonRegresa {
			background: #009efb;
			color: #fff;
			border-radius: 7px;
			/*box-shadow: 0 5px #119e4d;*/
			padding: 15px;
		}

		body {
			font-family: Arial, Helvetica, sans-serif;
		}

		#fondo {
			/*background-image: url("{{ asset('assets/images/factura_szone.jpg') }}");*/
			/* width: 892px; */
			/* height: 514px; */
		}

		#tablaProductos {
			font-size: 8pt;
			position: absolute;
			top: 230px;
			left: 0px;
			/* width: 718px; */
		}

		#codigoControlQr {
			font-size: 8pt;
			/* position: relative; */
			/*top: 230px;
			left: 0px;*/
			/* width: 718px; */
		}


		/*estilos para tablas de datos*/
		table.datos {
			/*font-size: 13px;*/
			/*line-height:14px;*/
			/* width: 1000; */
			border-collapse: collapse;
			background-color: #fff;
		}

		.datos th {
			height: 10px;
			background-color: #abd4ed;
			color: #000;
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

		#literalTotal {
			font-size: 8pt;
		}

		#datosEmpresaNit {
			/* font-weight: bold; */
			font-size: 10pt;
			position: absolute;
			top: 0px;
			left: 595px;
			padding: 10px;
			border: 1px solid black;
		}

		#datosEmpresaFactura {
			/* font-weight: bold; */
			font-size: 10pt;
			position: absolute;
			top: 180px;
			left: 0px;
			padding: 5px;
			/*border: 1px solid black;*/
			width: 891px;
		}

		#txtOriginal {
			font-weight: bold;
			font-size: 12pt;
			position: absolute;
			top: 85px;
			left: 670px;
			width: 150px;
			text-align: center;
		}

		#txtActividad {
			/* font-weight: bold; */
			font-size: 6pt;
			position: absolute;
			top: 110px;
			left: 600px;
			width: 280px;
			text-align: left;
		}

		#txtFactura {
			font-weight: bold;
			font-size: 19pt;
			position: absolute;
			top: 140px;
			left: 350px;
			width: 150px;
			text-align: center;
		}

		#logo {
			position: absolute;
			top: 20px;
			left: 20px;
		}

		#direccionEmpresa {
			font-weight: bold;
			font-size: 6pt;
			position: absolute;
			top: 85px;
			left: 20px;
			width: 150px;
			text-align: center;
		}
	</style>
	<script src="{{ asset('js/NumeroALetras.js') }}"></script>
	<script src="{{ asset('dist/js/qrcode.min.js') }}"></script>
</head>

<body>
@php
	function fechaCastellano ($fecha) {
	$fecha = substr($fecha, 0, 10);
	$numeroDia = date('d', strtotime($fecha));
	$dia = date('l', strtotime($fecha));
	$mes = date('F', strtotime($fecha));
	$anio = date('Y', strtotime($fecha));
	$dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
	$dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	$nombredia = str_replace($dias_EN, $dias_ES, $dia);
	$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre",
	"Noviembre", "Diciembre");
	$meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
	"November", "December");
	$nombreMes = str_replace($meses_EN, $meses_ES, $mes);
	return $numeroDia." de ".$nombreMes." de ".$anio;
	}
@endphp
	<div id="fondo">

		<div id="logo"><img src="{{ asset('assets/images/logoSmartZone.jpg') }}" width="150"></div>
			
			<table id="datosEmpresaNit" width="300">
				<tr>
					<th style="text-align: left;">NIT:</th>
					<td>{{ $datosEmpresa->nit }}</td>
				</tr>
				<tr>
					<th style="text-align: left;">FACTURA N&deg;:</th>
					<td>{{ $datosFactura->numero_factura }}</td>
				</tr>
				<tr>
					<th style="text-align: left;">N&deg; AUTORIZACION:</th>
					<td>{{ $datosFactura->numero_autorizacion }}</td>
				</tr>
			</table>
			
			<table id="datosEmpresaFactura">
				<tr>
					<td style="text-align: left;"><b>Lugar y Fecha:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;La Paz,
						{{ fechaCastellano($datosFactura->fecha_compra) }}</span></td>
					<td><b>NIT/CI:<b /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $datosFactura->nit_cliente }}</td>
				</tr>
				<tr>
					<td style="text-align: left;"><b>Se&ntilde;or(es):</b>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $productosVenta[0]->nombre }}</td>
					<td></td>
				</tr>
			</table>
		<div id="tablaProductos">
		<table class="datos" width="892">
			<thead>
				<tr>
					<th style="padding-top: 5px;padding-bottom: 5px;">N&deg;</th>
					<th>CANTIDAD</th>
					<th>DESCRIPCION</th>
					<th>PRECIO UNITARIO</th>
					<th>SUBTOTAL</th>
				</tr>
			</thead>			
			<tbody>
				@php
					$sumaSubTotal = 0;
				@endphp
				@foreach ($productosVenta as $con => $pv)
					<tr>
						<td width="25px">&nbsp;&nbsp;
							{{ ++$con }}
						</td>
						<td style="text-align: right;" width="100px">{{ $pv->cantidad }}</td>
						<td width="425px" style="text-align: left;">{{ $pv->producto }}</td>
						<td style="text-align: right;" width="100px">{{ $pv->precio_unitario }}</td>
						<td style="text-align: right;" width="100px"><b>{{ $pv->subtotal }}</b></td>
					</tr>
				@endforeach
				@php
					$numeroParaDecimal = number_format($datosFactura->monto_compra, 2, '.', '');
					list($numero, $decimal) = explode('.', $numeroParaDecimal);
				@endphp
			</tbody>
			<tfoot>
				<td colspan="3" style="text-align: left;">SON: <span id="literalTotal"> </span>{{ $decimal }}/100 BOLIVIANOS</td>
				<td style="background-color: #abd4ed;color: #000;">TOTAL Bs.</td>
				<td style="text-align: right;font-size: 9pt;font-weight: bold;">{{ number_format($datosFactura->monto_compra, 2, '.', '') }}</td>
			</tfoot>
			
		</table>
		<br />
			<table class="codigoControlQr" width="100%">
				<tr>
					<td>
						Codigo de Control: {{ $datosFactura->codigo_control }}<br />
						Fecha limite de Emision: {{ $datosFactura->fecha_limite }}
					</td>
					<td>
						<div id="qrcode"></div>
					</td>
				</tr>
			</table>
		<br />
		<center>
			"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAIS. EL USO ILICITO DE ESTA SERA SANCIONADO DE ACUERDO A LEY"<br />
			<b>Ley N&deg; 453: El proveedor debera suministrar el servicio en las modalidades y terminos ofertados o
				convenidos.</b>
			<p>&nbsp;</p>
			<div id="btnImprimir">
				<input type="button" id="botonImpresion" value="IMPRIMIR" onClick="window.print()">
				<input type="button" id="botonRegresa" value="VOLVER" onClick="vuelveSistema()">
			</div>
		</center>
		</div>

		<div id="txtOriginal">ORIGINAL</div>
		<div id="txtActividad">{{ $datosEmpresa->actividad }}</div>
		<div id="txtFactura">FACTURA</div>
		<div id="direccionEmpresa">{{ $datosEmpresa->direccion }}</div>
		
		</div>

	@php
		$fechaFactura = new DateTime($productosVenta[0]->fecha);
		$fechaQr = $fechaFactura->format('d/m/Y');
	@endphp

	<script>
		let valorTotal = Number({{ $datosFactura->monto_compra }});
		var options = { year: 'numeric', month: 'long', day: 'numeric' };
		// var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

		function numerosALetras() {

			// generamos los numeros a letras
		    valorLiteral = numeroALetras(valorTotal, {
		        plural: ' ',
		        singular: ' ',
		        centPlural: 'Centavos',
		        centSingular: 'Centavo'
		    });
			// console.log(valorLiteral);
			document.getElementById("literalTotal").innerHTML = valorLiteral+"con 00/100 Bs.";

			// cambiamos la fecha para mejor lectura
			let fechaHora="{{ $datosFactura->fecha_compra }}";
			let fechaSinHora = fechaHora.split(" ");
			let fecha = new Date(fechaSinHora[0]);
			// document.getElementById("lugarFecha").innerHTML = "La Paz, " + fecha.toLocaleDateString("es-ES", options);
		}

		function vuelveSistema(){
			window.location.href = "{{ url('Venta/tienda') }}";
		}

		window.onload = numerosALetras;
		let cadenaQr = "{{ $datosEmpresa->nit }}|{{ $datosFactura->numero_factura }}|{{ $datosFactura->numero_autorizacion }}|{{ $fechaQr }}|{{ number_format($datosFactura->monto_compra, 2, '.', '') }}|{{ round($datosFactura->monto_compra, 0, PHP_ROUND_HALF_UP) }}|{{ $datosFactura->codigo_control }}|{{ $datosFactura->nit_cliente }}|0|0|0|0";
		// console.log(cadenaQr);
		var qrcode = new QRCode("qrcode", {
			text: cadenaQr,
			width: 98,
			height: 90,
			colorDark : "#000000",
			colorLight : "#ffffff",
			correctLevel : QRCode.CorrectLevel.H
		});
	</script>
	
</body>
</html>