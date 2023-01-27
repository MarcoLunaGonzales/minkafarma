<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('funciones.php');
require('conexionmysql2.inc');

$rpt_fecha=cambia_formatofecha($rpt_fecha);

list($anio, $mes, $dia) = split('-', $rpt_fecha);
		
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";


$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp_nombre_territorio=mysqli_query($enlaceCon, $sql_nombre_territorio);
$datos_nombre_territorio=mysqli_fetch_array($resp_nombre_territorio);
$nombre_territorio=$datos_nombre_territorio[0];
$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
$resp_nombre_almacen=mysqli_query($enlaceCon, $sql_nombre_almacen);
$datos_nombre_almacen=mysqli_fetch_array($resp_nombre_almacen);
$nombre_almacen=$datos_nombre_almacen[0];
	echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Existencias Almacen<br>Territorio: <strong>$nombre_territorio</strong> Nombre Almacen: <strong>$nombre_almacen</strong> <br>Existencias a Fecha: <strong>$rpt_fecha</strong><br>$txt_reporte</th></tr></table>";
	//desde esta parte viene el reporte en si
	$sql_item="select codigo_material, descripcion_material from material_apoyo
	where codigo_material<>0 and estado='Activo' order by descripcion_material";
	
	$resp_item=mysqli_query($enlaceCon, $sql_item);
	
	echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='70%'>
	<tr>
	<th>&nbsp;</th>
	<th>Código</th>
	<th>Material</th>
	<th>Cantidad</th>
	<th>Costo Unitario</th>
	<th>Valor</th>
	</tr>";
	
	$indice=1;
	$totalValorAlmacen=0;
	while($datos_item=mysqli_fetch_array($resp_item))
	{	$codigo_item=$datos_item[0];
		$codigo_anterior=$datos_item[3];

		$nombre_item=$datos_item[1];

		$cadena_mostrar="<tr><td>$indice</td><td>$codigo_item</td><td>$nombre_item</td>";

		$sql_ingresos="select sum(id.cantidad_unitaria), sum(id.cantidad_unitaria*costo_almacen) from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$rpt_almacen'
		and id.cod_material='$codigo_item' and i.ingreso_anulado=0";
		//echo $sql_ingresos;
		$resp_ingresos=mysqli_query($enlaceCon, $sql_ingresos);
		$dat_ingresos=mysqli_fetch_array($resp_ingresos);
		$cant_ingresos=$dat_ingresos[0];
		$costo_ingresos=$dat_ingresos[1];
		
		$sql_salidas="select sum(sd.cantidad_unitaria), sum(sd.cantidad_unitaria*costo_almacen) from salida_almacenes s, salida_detalle_almacenes sd
		where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$rpt_almacen'
		and sd.cod_material='$codigo_item' and s.salida_anulada=0";
		//echo $sql_salidas;
		$resp_salidas=mysqli_query($enlaceCon, $sql_salidas);
		$dat_salidas=mysqli_fetch_array($resp_salidas);
		$cant_salidas=$dat_salidas[0];
		$costo_salidas=$dat_salidas[1];
		
		$stock2=$cant_ingresos-$cant_salidas;
		$valorItem=$costo_ingresos-$costo_salidas;
		$valorItem=redondear2($valorItem);
		
		$costoUnitario=0;
		if($stock2>0){
			$costoUnitario=$valorItem/$stock2;
		}
		$costoUnitario=redondear2($costoUnitario);
		
		$totalValorAlmacen=$totalValorAlmacen+$valorItem;
		
		//sacamos los costos
		
		$sqlCosto="select id.costo_promedio from ingreso_almacenes i, ingreso_detalle_almacenes id where i.cod_ingreso_almacen=id.cod_ingreso_almacen and 
		i.cod_almacen='$rpt_almacen' and id.cod_material='$codigo_item' and i.ingreso_anulado=0 and 
		i.fecha<='$rpt_fecha' and id.costo_promedio<>0 ORDER BY i.cod_ingreso_almacen desc limit 0,1";
		$respCosto=mysqli_query($enlaceCon, $sqlCosto);
		$nroFilasCosto=mysqli_num_rows($respCosto);
		if($nroFilasCosto==1){
			$costoUnitario=mysql_result($respCosto,0,0);
		}else{
			$costoUnitario=0;
		}
		
		
		$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
		where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$rpt_almacen'";
		$resp_stock=mysqli_query($enlaceCon, $sql_stock);
		$dat_stock=mysqli_fetch_array($resp_stock);
		$stock_real=$dat_stock[0];
		
		//$valorItem=$stock2*$costoUnitario;
		
		
		if($stock_real=="")
		{	$stock_real=0;
		}
		if($stock2<0)
		{	$cadena_mostrar.="<td align='center'>0</td><td align='center'>$costoUnitario</td><td align='center'>$valorItem</td></tr>";
		}
		else
		{	$cadena_mostrar.="<td align='center'>$stock2</td><td align='center'>$costoUnitario</td><td align='center'>$valorItem</td></tr>";
		}
		
		$sql_linea="select * from material_apoyo where codigo_material='$codigo_item'";
		$resp_linea=mysqli_query($enlaceCon, $sql_linea);
		
		$num_filas=mysqli_num_rows($resp_linea);
		if($rpt_linea!=0 and $num_filas==0)
		{	//no se muestra nada
		}
		else
		{	if($rpt_ver==1)
			{	echo $cadena_mostrar;
			}
			if($rpt_ver==2 and $stock_real>0)
			{	echo $cadena_mostrar;
			}
			if($rpt_ver==3 and $stock_real==0)
			{	echo $cadena_mostrar;
			}
			$indice++;
		}
	}
	$totalValorAlmacen=redondear2($totalValorAlmacen);
	echo "<tr><td></td><td></td><td></td><td></td><td></td><td align='right'>$totalValorAlmacen</td><tr>";
	echo "</table>";
include("imprimirInc.php");
?>