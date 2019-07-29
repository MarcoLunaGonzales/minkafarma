<?php
	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	echo "<form method='post' action=''>";
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo 
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.cod_ingreso_almacen='$codigo_ingreso'";
	
	//echo $sql;
	
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Detalle de Ingreso</th></tr></table></center><br>";
	
	echo "<table border='0' class='texto' align='center'>";
	echo "<tr><th>Nro. de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td></tr>";
	echo "</table>";
	$sql_detalle="select i.cod_material, i.cantidad_unitaria, i.precio_neto, i.lote, DATE_FORMAT(i.fecha_vencimiento, '%d/%m/%Y'),
	(select u.nombre from ubicaciones_estantes u where u.codigo=i.cod_ubicacionestante)as estante,
	(select u.nombre from ubicaciones_filas u where u.codigo=i.cod_ubicacionfila)as fila
	from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='$codigo' and m.codigo_material=i.cod_material";
	$resp_detalle=mysql_query($sql_detalle);
	echo "<br><table border=0 class='texto' align='center'>";
	echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad</th><th>Lote</th><th>Fecha Vencimiento</th><th>Ubicacion</th><th>Precio Compra(Bs.)</th><th>Total(Bs.)</th></tr>";
	$indice=1;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$precioNeto=redondear2($dat_detalle[2]);
		$loteProducto=$dat_detalle[3];
		$fechaVenc=$dat_detalle[4];
		$estante=$dat_detalle[5];
		$fila=$dat_detalle[6];
		
		$totalValorItem=$cantidad_unitaria*$precioNeto;
		
		$cantidad_unitaria=redondear2($cantidad_unitaria);
		$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];
		echo "<tr><td align='center'>$indice</td><td>$nombre_material</td><td align='center'>$cantidad_unitaria</td>
		<td align='center'>$loteProducto</td>
		<td align='center'>$fechaVenc</td>
		<td align='center'>$estante - $fila</td>
		<td align='center'>$precioNeto</td><td align='center'>$totalValorItem</td></tr>";
		$indice++;
	}
	echo "</table>";
	
	echo "<center><a href='javascript:window.print();'><IMG border='no'
	 src='imagenes/print.jpg' width='40'></a></center>";
	
?>