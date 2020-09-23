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


	</style>
	<script src="{{ asset('js/NumeroALetras.js') }}"></script>
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
		<div id="literalTotal">

		</div>
		
	</div>

	<script>
		let valorTotal = Number({{ $sumaSubTotal }});
		function numerosALetras() {
		    valorLiteral = numeroALetras(valorTotal, {
		        plural: 'BOLIVIANOS',
		        singular: 'Bolivianos',
		        centPlural: 'Centavos',
		        centSingular: 'Centavo'
		    });
			console.log(valorLiteral);
			document.getElementById("literalTotal").innerHTML = valorLiteral;
		}

		window.onload = numerosALetras;
	</script>
	
</body>
</html>