<?php
	require("conexionmysqli.php");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	echo "<form method='post' action=''>";

	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo 
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.cod_ingreso_almacen='$codigo_ingreso'";
	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<center><table border='0' class='textotit' align='center' width='100%'><tr><th align='center'>Detalle de Ingreso</th></tr></table></center><br>";
	
	echo "<table border='0' class='texto' align='center'>";
	echo "<tr><th>Nro. de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Proveedor</th><th>Nro. Factura</th>
	<th>Observaciones</th></tr>";
	$dat=mysqli_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
	
	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_ingreso_mostrar</td>
	<td>$nombre_tipoingreso</td><td>-</td><td>-</td>
	<td>&nbsp;$obs_ingreso</td></tr>";
	echo "</table>";
	$sql_detalle="select i.cod_material, i.cantidad_unitaria, i.precio_neto, i.lote, DATE_FORMAT(i.fecha_vencimiento, '%d/%m/%Y'),
	(select u.nombre from ubicaciones_estantes u where u.codigo=i.cod_ubicacionestante)as estante,
	(select u.nombre from ubicaciones_filas u where u.codigo=i.cod_ubicacionfila)as fila, i.descuento_unitario, i.precio_bruto, m.cantidad_presentacion
	from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='$codigo' and m.codigo_material=i.cod_material";
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
	echo "<br><table border=0 class='texto' align='center'>";
	echo "<tr><th>&nbsp;</th>
		<th>Codigo</th>
		<th>Producto</th>
		<th>Vencimiento</th>
		<th>Cantidad Caja</th>
		<th>Cantidad Unitaria</th>
		<th>Precio Compra</th>
		<th>Subtotal</th>
		<th>Descuento %</th>
		<th>Descuento Bs.</th>
		<th>Total(Bs.)</th>
	</tr>";
	$indice=1;
	$totalIngreso=0;
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$precioNeto=redondear2($dat_detalle[2]);
		$loteProducto=$dat_detalle[3];
		$fechaVenc=$dat_detalle[4];
		$estante=$dat_detalle[5];
		$fila=$dat_detalle[6];
		
		// $totalValorItem=$cantidad_unitaria*$precioNeto;
		$precioBruto=redondear2($dat_detalle[8]);
		$cantidadPresentacion=$dat_detalle[9];

		$cantidadCaja=$cantidad_unitaria/$cantidadPresentacion;

		# Nuevo valores #
		$precioBruto=redondear2($dat_detalle[8]*$cantidadPresentacion);

		$totalValorItem=$cantidadCaja*$precioBruto;

		$descuento_numerico   = $totalValorItem * ($dat_detalle[7]/100);
		$descuento_porcentaje = $dat_detalle[7];
		$total_monto = $totalValorItem-$descuento_numerico;
		#***************#

		$totalIngreso+=$totalValorItem-$descuento_numerico;

		$totalValorItemF=formatonumeroDec($totalValorItem-$descuento_numerico);


		$cantidad_unitaria=redondear2($cantidad_unitaria);
		$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
		$resp_nombre_material=mysqli_query($enlaceCon,$sql_nombre_material);
		$dat_nombre_material=mysqli_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];


		echo "<tr>
		<td align='center'>$indice</td>
		<td align='center'>$cod_material</td>
		<td>$nombre_material</td>
		<td align='center'>$fechaVenc</td>
		<td align='center'>$cantidadCaja</td>
		<td align='center'>$cantidad_unitaria</td>
		<td align='center'>$precioBruto</td>
		<td align='center'>".redondear2($cantidadCaja*$precioBruto)."</td>
		<td align='center'>$descuento_porcentaje</td>
		<td align='center'>$descuento_numerico</td>
		<td align='center'>$totalValorItemF</td></tr>";
		$indice++;
	}
	$totalIngresoF=formatonumeroDec($totalIngreso);
	echo "<tr>
		<td align='center'>-</td>
		<td align='center'>&nbsp;</td>
		<td>&nbsp;</td><td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>Total Compra [Bs.]</td><td align='center'>$totalIngresoF</td></tr>";
	echo "</table>";
	
	echo "<center><a href='javascript:window.print();'><IMG border='no'
	 src='imagenes/print.jpg' width='40'></a></center>";
	
?>