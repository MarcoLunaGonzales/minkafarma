<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");

	$sqlEmpresa="select nombre, nit, direccion from datos_empresa";
	$respEmpresa=mysql_query($sqlEmpresa);
	$nombreEmpresa=mysql_result($respEmpresa,0,0);
	$nitEmpresa=mysql_result($respEmpresa,0,1);
	$direccionEmpresa=mysql_result($respEmpresa,0,2);
	
	
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones,
	s.nro_correlativo, s.territorio_destino, s.almacen_destino, (select c.nombre_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	(select c.dir_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	s.monto_total, s.descuento, s.monto_final
	FROM salida_almacenes s, tipos_salida ts
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and s.cod_salida_almacenes='$codigo_salida'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];
	$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$nro_correlativo=$dat[4];
	$territorio_destino=$dat[5];
	$almacen_destino=$dat[6];
	$nombreCliente=$dat[7];
	$direccionCliente=$dat[8];
	$montoNota=$dat[9];
	$montoNota=redondear2($montoNota);
	$descuentoNota=$dat[10];
	$descuentoNota=redondear2($descuentoNota);
	$montoFinal=$dat[11];
	$montoFinal=redondear2($montoFinal);
	
		/*$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$territorio_destino'";
		$resp_nombre_territorio=mysql_query($sql_nombre_territorio);
		$dat_nombre_territorio=mysql_fetch_array($resp_nombre_territorio);
		$nombre_territorio=$dat_nombre_territorio[0];
		
		$sql_nombre_almacen_destino="select nombre_almacen from almacenes where cod_almacen='$almacen_destino'";
		$resp_nombre_almacen=mysql_query($sql_nombre_almacen_destino);
		$dat_nombre_almacen_destino=mysql_fetch_array($resp_nombre_almacen);
		$nombre_almacen_destino=$dat_nombre_almacen_destino[0];*/
		
	echo "<table border='0' class='bordeNegroTdMod' cellspacing='0' width='90%' align='center'>";
	echo "<tr><td align='left' width='30%' class='bordeNegroTdMod'>$nombreEmpresa</td>
	<td align='center' width='30%' class='bordeNegroTdMod'>Nota de Remision<br>Nro. $nro_correlativo</td>
	<td align='right' width='30%' class='bordeNegroTdMod'>Fecha: $fecha_salida_mostrar</td></tr>";
	
	echo "<tr><td align='left' class='bordeNegroTdMod'>Cliente: $nombreCliente<br>Direccion: $direccionCliente</td>
	<td align='center' class='bordeNegroTdMod'>NIT: $nitCliente</td><td align='right'>Observaciones: $obs_salida</td></tr>";
			
	echo "</table><br>";

	echo "<table border='0' class='bordeNegroTdMod' cellspacing='0' width='90%' align='center'>";
	echo "<tr><td class='bordeNegroTdMod'>Material</td><td class='bordeNegroTdMod'>Codigo</td><td class='bordeNegroTdMod'>Cantidad</td>
	<td class='bordeNegroTdMod'>Peso U.</td><td class='bordeNegroTdMod'>Peso Total</td><td class='bordeNegroTdMod'>Precio</td>
		<td class='bordeNegroTdMod'>Desc. U.</td><td class='bordeNegroTdMod'>Importe</td></tr>";
	echo "<tr><td colspan='8'>&nbsp;</td></tr>";
	echo "<form method='post' action=''>";
	
	$sql_detalle="select s.cod_material, m.descripcion_material, m.orden_grupo, m.peso,
		s.cantidad_unitaria, s.precio_unitario, s.`descuento_unitario`, s.`monto_unitario` 
		from salida_detalle_almacenes s, material_apoyo m
		where s.cod_salida_almacen='$codigo' and s.cod_material=m.codigo_material";
	
	$resp_detalle=mysql_query($sql_detalle);
	$indice=0;
	$montoTotal=0;
	$pesoTotal=0;

	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$nombre_material=$dat_detalle[1];
		$codigoInterno=$dat_detalle[2];
		$peso=$dat_detalle[3];
		$cantidad_unitaria=$dat_detalle[4];
		$precioUnitario=$dat_detalle[5];
		$precioUnitario=redondear2($precioUnitario);
		$descuentoUnitario=$dat_detalle[6];
		$descuentoUnitario=redondear2($descuentoUnitario);
		$montoUnitario=$dat_detalle[7];
		$montoUnitario=redondear2($montoUnitario);
		$pesoItem=$peso*$cantidad_unitaria;
		$pesoItem=redondear2($pesoItem);
		$pesoTotal=$pesoTotal+$pesoItem;
		$pesoTotal=redondear2($pesoTotal);
		
		echo "<tr><td class='bordeNegroTdMod'>$nombre_material</td>
			<td align='center' class='bordeNegroTdMod'>$codigoInterno</td>
			<td class='bordeNegroTdMod'>$cantidad_unitaria</td>
			<td class='bordeNegroTdMod'>$peso</td><td class='bordeNegroTdMod'>$pesoItem</td>
			<td class='bordeNegroTdMod'>$precioUnitario</td>
			<td class='bordeNegroTdMod'>$descuentoUnitario</td>
			<td class='bordeNegroTdMod' align='center'>$montoUnitario</td></tr>";
		$indice++;
		$montoTotal=$montoTotal+$montoUnitario;
		$montoTotal=redondear2($montoTotal);
	
	}
	
	for($j=$indice; $j<=15; $j++){
		echo "<tr><td>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td><td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align='center'>-</td></tr>";
	}
	
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th></th><th>Total Venta</th><th>$montoNota</th></tr>";
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th></th><th>Descuento</th><th>$descuentoNota</th></tr>";
	echo "<tr><th></th><th></th><th></th><th>Total Peso:</th><th>$pesoTotal</th><th></th><th>Total Final</th><th>$montoFinal</th></tr>";
	echo "</table><br><br>";
	echo "<div><table width='90%'>
	<tr class='bordeNegroTdMod'><td width='33%' align='center'>Despachado</td><td width='33%' align='center'>Entregue Conforme</td><td width='33%' align='center'>Recibi Conforme</td></tr>
	</table></div>";
?>