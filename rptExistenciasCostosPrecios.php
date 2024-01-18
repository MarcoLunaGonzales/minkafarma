<?php

 error_reporting(E_ALL);
 ini_set('display_errors', '1');
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=archivo.xls");
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli2.inc');
require('funciones.php');

 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$rpt_territorio=$_GET["rpt_territorio"];
$rpt_almacen=$_GET["rpt_almacen"];
$rpt_ver=$_GET["rpt_ver"];
$rpt_fecha=$_GET["rpt_fecha"];
$rptOrdenar=$_GET["rpt_ordenar"];
$rptDistribuidor=$_GET["rpt_distribuidor"];

$rpt_fecha=cambia_formatofecha($rpt_fecha);
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
		
		if($rptOrdenar==1){
			$sql_item="select m.codigo_material, m.descripcion_material, m.cantidad_presentacion from material_apoyo m, proveedores p, proveedores_lineas pl
			where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor and m.codigo_material<>0 and m.estado='1' and p.cod_proveedor in ($rptDistribuidor) order by m.descripcion_material";
		}else{
			$sql_item="select m.codigo_material, 
			m.descripcion_material, cantidad_presentacion, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor)as linea  from proveedores p, proveedores_lineas pl, 
			material_apoyo m where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor and m.estado='1' and p.cod_proveedor in ($rptDistribuidor) order by 4,2";
		}
		
		//echo $sql_item;
		$resp_item=mysqli_query($enlaceCon, $sql_item);
		
		if($rptOrdenar==1){
			echo "<br><table border=0 align='center' class='textomediano' width='70%'>
			<thead>
			<tr><th>&nbsp;</th><th>Codigo</th><th>Material</th>
			<th>CantidadPresentacion</th><th>Cajas</th><th>Unidades</th><th>Costo Unitario</th><th>Costo</th></tr>
			</thead>";
		}else{
			echo "<br><table border=0 align='center' class='textomediano' width='70%'>
			<thead>
			<tr><th>&nbsp;</th><th>Codigo</th><th>Linea Proveedor</th><th>Material</th>
			<th>CantidadPresentacion</th><th>Cajas</th><th>Unidades</th><th>Costo Unitario</th><th>Costo</th></tr>
			</thead>";
		}

		
		$indice=1;
		$cadena_mostrar="<tbody>";
		
		$totalCosto=0;

		while($datos_item=mysqli_fetch_array($resp_item))
		{	$codigo_item=$datos_item[0];
			$nombre_item=$datos_item[1];
			$cantidadPresentacion=$datos_item[2];
			
			$nombreLinea="";
		
			if($rptOrdenar==1){
				$cadena_mostrar="<tr><td>$indice</td><td>$codigo_item</td><td>$nombre_item</td><td>$cantidadPresentacion</td>";
			}else{
				$nombreLinea=$datos_item[3];
				$cadena_mostrar="<tr><td>$indice</td><td>$codigo_item</td><td>$nombreLinea</td><td>$nombre_item</td><td>$cantidadPresentacion</td>";				
			}

			
			$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$rpt_almacen'
			and id.cod_material='$codigo_item' and i.ingreso_anulado=0";
			$resp_ingresos=mysqli_query($enlaceCon, $sql_ingresos);
			$dat_ingresos=mysqli_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$rpt_almacen'
			and sd.cod_material='$codigo_item' and s.salida_anulada=0";
			$resp_salidas=mysqli_query($enlaceCon, $sql_salidas);
			$dat_salidas=mysqli_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;

			/*$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$rpt_almacen'";
			$resp_stock=mysqli_query($enlaceCon, $sql_stock);
			$dat_stock=mysqli_fetch_array($resp_stock);
			$stock_real=$dat_stock[0];
			if($stock_real=="")
			{	$stock_real=0;
			}
			*/
			$stock_real=$stock2;

			$costoItem=0;
			if($stock2<0)
			{	//$cadena_mostrar.="<td align='center'>0</td></tr>";
				$cadena_mostrar.="<td align='center'>0</td><td align='center'>$stock2</td></tr>";
			}
			else{	
				if($stock2>=$cantidadPresentacion){
					$stockCajas=intval($stock2/$cantidadPresentacion);
				}else{
					$stockCajas=0;
				}
				$stockUnidades=0;
				if($cantidadPresentacion>0){
					$stockUnidades=$stock2%$cantidadPresentacion;
				}
				$precioUnitario=costoVentaGeneral($enlaceCon,$codigo_item,$rpt_territorio);
				$costoItem=$stock2*$precioUnitario;				
				
				$costoItemF=formatonumeroDec($costoItem);
				/*FIN COSTO*/
		
				$cadena_mostrar.="<td align='center'>$stockCajas</td><td align='center'>$stockUnidades</td>
				<td align='right'>$precioUnitario</td>
				<td align='center'>$costoItemF</td></tr>";
			}

			$totalCosto=$totalCosto+$costoItem;

			$sql_linea="select * from material_apoyo where codigo_material='$codigo_item'";
			$resp_linea=mysqli_query($enlaceCon, $sql_linea);
			
			$num_filas=mysqli_num_rows($resp_linea);
			if($num_filas==0)
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
		$cadena_mostrar.="</tbody>";
		echo "</table>";

		$totalCostoF=formatonumeroDec($totalCosto);

		echo "<table border=0 align='center' class='textomediano' width='70%'>
		<tr>
			<td align='right'>$totalCostoF</td>
		</tr>
		</table>";

?>