<?php

	require("conexionmysqli.php");
	require('estilos.inc');
	echo "<form method='post' action=''>";
	
	$sql="select o.cod_orden, o.fecha_orden, o.nro_orden, o.observaciones, p.nombre_proveedor, t.nombre_tipopago, o.descuento
	FROM orden_compra o, proveedores p, tipos_pago t
	where o.cod_proveedor=p.cod_proveedor and t.cod_tipopago=o.tipo_pago and o.cod_orden='$codigo_orden'";

	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Detalle de Orden de Compra</h1>";
	echo "<center><table class='texto'>";
	echo "<tr><th>Nro. de OC</th><th>Fecha</th><th>Proveedor</th><th>Tipo Pago</th><th>Observaciones</th></tr>";
	$dat=mysqli_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$nroOC=$dat[2];
	$fechaOCmostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombreProveedor=$dat[4];
	$obsOC=$dat[3];
	$tipoPago=$dat[5];
	$descuento=$dat[6];

	echo "<tr><td align='center'>$nroOC</td>
	<td align='center'>$fechaOCmostrar</td>
	<td>$nombreProveedor</td>
	<td>$tipoPago</td>
	
	<td>&nbsp;$obsOC</td></tr>";
	echo "</table>";
	
	$sql_detalle="select od.`cod_orden`, m.`descripcion_material`, od.`cantidad`, od.`precio_unitario`, lote, fecha_vencimiento from `orden_compra_detalle` od, `material_apoyo` m
		where od.`cod_material`=m.`codigo_material` and od.`cod_orden`=$codigo_orden";
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
	echo "<br><table class='texto'>";
	
	echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad</th><th>Lote</th><th>FechaVenc.</th><th>Precio</th><th>Monto</th></tr>";
	
	$indice=1;
	$montoTotal=0;
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$nombreMaterial=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$precio=$dat_detalle[3];
		$monto=$precio;
		$loteProd=$dat_detalle[4];
		$fechaVenc=$dat_detalle[5];
	
		echo "<tr><td align='center'>$indice</td><td>$nombreMaterial</td><td align='center'>$cantidad</td>
		<td align='center'>$loteProd</td>
		<td align='center'>$fechaVenc</td>
		<td align='center'>$precio</td><td align='center'>$monto</td></tr>";
		$indice++;
		$montoTotal=$montoTotal+$monto;
	}
	echo "<tr><th colspan='6'>Monto Total:</th><th>$montoTotal</th></tr>";
	echo "<tr><th colspan='6'>Descuento:</th><th>$descuento</th></tr>";
	$totalTotal=$montoTotal-$descuento;
	echo "<tr><th colspan='6'>Total - descuento</th><th>$totalTotal</th></tr>";
	echo "</table>";
	echo "<br><center>
	<a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.jpg' width='40'></a>";

//	echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
	
?>