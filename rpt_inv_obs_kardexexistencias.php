<?php
require('estilos_reportes_almacencentral.php');
require('conexionmysqli.inc');
require('function_formatofecha.php');
require('function_comparafechas.php');

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

echo "$rpt_territorio $rpt_almacen $tipo_item";
	$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_territorio=mysqli_query($enlaceCon, $sql_nombre_territorio);
	$dat_territorio=mysqli_fetch_array($resp_territorio);
	$nombre_territorio=$dat_territorio[0];
	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysqli_query($enlaceCon, $sql_nombre_almacen);
	$dat_almacen=mysqli_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$dat_almacen[0];
	echo "<table align='center' class='textotit'><tr><td align='center'>Reporte Observaciones entre Kardex de Existencia Fisica y Existencias<br>Territorio: <strong>$nombre_territorio</strong> Almacen: <strong>$nombre_almacen</strong>Tipo de Item: <strong>$nombre_tipoitem</strong></th></tr></table>";
	echo "<center><br><table border='1' class='texto' cellspacing='0' width='100%'>";
	echo "<tr class='textomini'><th>Codigo</th><th>Item</th><th>Saldo segun Kardex</th><th>Saldo segun Existencias</th></tr>";	
	
	$sql_item="select codigo_material, descripcion_material from material_apoyo where codigo_material<>0 order by descripcion_material";
	$resp_item=mysqli_query($enlaceCon, $sql_item);
	while($dat_item=mysqli_fetch_array($resp_item))
	{	$codigo_item=$dat_item[0];
		$nombre_item=$dat_item[1];		
		$sql_fechas_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and 
		i.ingreso_anulado=0 and id.cod_material='$codigo_item'";
		$resp_fechas_ingresos=mysqli_query($enlaceCon, $sql_fechas_ingresos);
		$dat_kardex_ingresos=mysqli_fetch_array($resp_fechas_ingresos);
		$cantidad_ingreso_kardex=$dat_kardex_ingresos[0];
		$sql_fechas_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
		where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and 
		s.salida_anulada=0 and sd.cod_material='$codigo_item'";
		$resp_fechas_salidas=mysqli_query($enlaceCon, $sql_fechas_salidas);
		$dat_kardex_salidas=mysqli_fetch_array($resp_fechas_salidas);
		$cantidad_salida_kardex=$dat_kardex_salidas[0];
		$cantidad_kardex=$cantidad_ingreso_kardex-$cantidad_salida_kardex;
		//por existencias
		$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
		where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$rpt_almacen'";
		$resp_stock=mysqli_query($enlaceCon, $sql_stock);
		$dat_stock=mysqli_fetch_array($resp_stock);
		$stock_real=$dat_stock[0];
		if($cantidad_kardex!=$stock_real)
		{	echo "<tr><td align='left'>$codigo_item</td><td align='left'>$nombre_item</td><td align='right'>$cantidad_kardex</td><td align='right'>$stock_real<td></tr>";
		}		
	}
	echo "</table></center><br>";	
	echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>