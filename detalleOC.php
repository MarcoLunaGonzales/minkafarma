<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	require('estilos.inc');
	echo "<form method='post' action=''>";
	$sql="select o.cod_orden, o.fecha_orden, o.nro_orden, o.observaciones, p.nombre_proveedor, t.nombre_tipopago, o.descuento
	FROM orden_compra o, proveedores p, tipos_pago t
	where o.cod_proveedor=p.cod_proveedor and t.cod_tipopago=o.tipo_pago and o.cod_orden='$codigo_orden'";

	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Detalle de OC</th></tr></table></center><br>";
	echo "<table border='1' class='texto' cellspacing='0' width='70%' align='center'>";
	echo "<tr><th>Número de OC</th><th>Fecha</th><th>Proveedor</th><th>Tipo Pago</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
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
	
	$sql_detalle="select od.`cod_orden`, m.`descripcion_material`, od.`cantidad`, od.`precio_unitario` from `orden_compra_detalle` od, `material_apoyo` m
		where od.`cod_material`=m.`codigo_material` and od.`cod_orden`=$codigo_orden";
	$resp_detalle=mysql_query($sql_detalle);
	echo "<br><table border=1 cellspacing='0' class='textomini' width='70%' align='center'>";
	
	echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad</th><th>Precio</th><th>Monto</th></tr>";
	
	$indice=1;
	$montoTotal=0;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$nombreMaterial=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$precio=$dat_detalle[3];
		$monto=$cantidad*$precio;
	
		echo "<tr><td align='center'>$indice</td><td>$nombreMaterial</td><td align='center'>$cantidad</td>
		<td align='center'>$precio</td><td align='center'>$monto</td></tr>";
		$indice++;
		$montoTotal=$montoTotal+$monto;
	}
	echo "<tr><th colspan='4'>Monto Total:</th><th>$montoTotal</th></tr>";
	echo "<tr><th colspan='4'>Descuento:</th><th>$descuento</th></tr>";
	$totalTotal=$montoTotal-$descuento;
	echo "<tr><th colspan='4'>Total - descuento</th><th>$totalTotal</th></tr>";
	echo "</table>";
	echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

//	echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
	
?>