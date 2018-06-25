<?php
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=archivo.xls");
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
$rpt_fecha=cambia_formatofecha($rpt_fecha);
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";


if($rpt_territorio!=0)
{	$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_nombre_territorio=mysql_query($sql_nombre_territorio);
	$datos_nombre_territorio=mysql_fetch_array($resp_nombre_territorio);
	$nombre_territorio=$datos_nombre_territorio[0];
	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysql_query($sql_nombre_almacen);
	$datos_nombre_almacen=mysql_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$datos_nombre_almacen[0];
		echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Existencias Almacen<br>Territorio: <strong>$nombre_territorio</strong> Nombre Almacen: <strong>$nombre_almacen</strong> <br>Existencias a Fecha: <strong>$rpt_fecha</strong><br>$txt_reporte</th></tr></table>";
		//desde esta parte viene el reporte en si
		$sql_item="select codigo_material, descripcion_material from material_apoyo
		where codigo_material<>0 and estado='Activo' order by descripcion_material";
		
		$resp_item=mysql_query($sql_item);
		
		echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='70%'><tr><th>&nbsp;</th><th>Código</th><th>Material</th>
		<th>Cantidad</th></tr>";
		
		$indice=1;
		while($datos_item=mysql_fetch_array($resp_item))
		{	$codigo_item=$datos_item[0];
			$codigo_anterior=$datos_item[3];

			$nombre_item=$datos_item[1];

			$cadena_mostrar="<tr><td>$indice</td><td>$codigo_item</td><td>$nombre_item</td>";

			$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$rpt_almacen'
			and id.cod_material='$codigo_item' and i.ingreso_anulado=0";
			$resp_ingresos=mysql_query($sql_ingresos);
			$dat_ingresos=mysql_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$rpt_almacen'
			and sd.cod_material='$codigo_item' and s.salida_anulada=0";
			$resp_salidas=mysql_query($sql_salidas);
			$dat_salidas=mysql_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;

			$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$rpt_almacen'";
			$resp_stock=mysql_query($sql_stock);
			$dat_stock=mysql_fetch_array($resp_stock);
			$stock_real=$dat_stock[0];
			if($stock_real=="")
			{	$stock_real=0;
			}
			if($stock2<0)
			{	$cadena_mostrar.="<td align='center'>0</td></tr>";
			}
			else
			{	$cadena_mostrar.="<td align='center'>$stock2</td></tr>";
			}
			
			$sql_linea="select * from material_apoyo where codigo_material='$codigo_item'";
			$resp_linea=mysql_query($sql_linea);
			
			$num_filas=mysql_num_rows($resp_linea);
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
		echo "</table>";
		echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
}
//aqui sacamos el reporte para todas las regionales
if($rpt_territorio==0)
{		if($rpt_linea!=0)
		{	$sql_linea="select nombre_linea from lineas where codigo_linea='$rpt_linea'";
			$resp_linea=mysql_query($sql_linea);
			$dat_linea=mysql_fetch_array($resp_linea);
			$nombre_linea=$dat_linea[0];
			$txt_linea="Línea: <strong>$nombre_linea</strong>";
		}
		if($tipo_item==1){$nombre_item="Muestra Médica";}else{$nombre_item="Material de Apoyo";}
		echo "<table align='center' class='textotit'><tr><td align='center'>Reporte Existencias Almacen<br>Todos los territorios </strong> $txt_linea<BR>Existencias a Fecha: <strong>$rpt_fecha</strong><BR>$txt_reporte</th></tr></table>";
		//desde esta parte viene el reporte en si
		if($tipo_item==1)
		{	$sql_item="select codigo, descripcion, presentacion 
			from muestras_medicas order by descripcion, presentacion limit 1,10";
		}
		if($tipo_item==2)
		{	if($rpt_tipomaterial==0)
			{	$sql_item="select codigo_material, descripcion_material from material_apoyo
				where codigo_material<>0 order by descripcion_material";
			}
			else
			{	$sql_item="select codigo_material, descripcion_material from material_apoyo
				where codigo_material<>0 and cod_tipo_material='$rpt_tipomaterial' order by descripcion_material";
			}
		}
		$resp_item=mysql_query($sql_item);
		$indice=1;
		$sql_almacenes="select a.cod_almacen, a.cod_ciudad, a.nombre_almacen, c.descripcion from almacenes a, ciudades c where a.cod_ciudad=c.cod_ciudad
		order by c.descripcion";
		$resp_almacenes=mysql_query($sql_almacenes);
		if($tipo_item==1){echo "<br><table cellspacing='0' border=1 align='center' class='texto'><tr><th>&nbsp;</th><th>Muestra</th><th>Línea</th>";}
		if($tipo_item==2){echo "<br><table cellspacing='0' border=1 align='center' class='texto'><tr><th>&nbsp;</th><th>Material de Apoyo</th><th>Línea</th><th>Tipo Material</th>";}
		while($datos_almacenes=mysql_fetch_array($resp_almacenes))
		{	$rpt_almacen=$datos_almacenes[0];
			$nombre_almacen=$datos_almacenes[2];
			$nombre_ciudad=$datos_almacenes[3];
			//echo "<th>$nombre_ciudad<br>$nombre_almacen</th>";
			echo "<th class='textomini'>$nombre_ciudad</th>";
		}
		echo "<th>Total</th>";
		echo "</tr>";
		while($datos_item=mysql_fetch_array($resp_item))
		{	$codigo_item=$datos_item[0];
			if($tipo_item==1)
			{	$nombre_item="$datos_item[1] $datos_item[2]";
				$sql_nombre_linea="select l.nombre_linea from muestras_medicas m, lineas l
				where m.codigo_linea=l.codigo_linea and m.codigo='$codigo_item'";
				$resp_nombre_linea=mysql_query($sql_nombre_linea);
				$dat_nombre_linea=mysql_fetch_array($resp_nombre_linea);
				$nombre_linea=$dat_nombre_linea[0];
				$cadena_mostrar="<tr><td>$indice</td><td><strong>$nombre_item</strong></td><td>&nbsp;$nombre_linea</td>";
			}
			if($tipo_item==2)
			{	$nombre_item=$datos_item[1];
				$sql_nombre_linea="select l.nombre_linea from material_apoyo m, lineas l
				where m.codigo_linea=l.codigo_linea and m.codigo_material='$codigo_item'";
				$resp_nombre_linea=mysql_query($sql_nombre_linea);
				$dat_nombre_linea=mysql_fetch_array($resp_nombre_linea);
				$nombre_linea=$dat_nombre_linea[0];

				$sql_tipomaterial="select t.nombre_tipomaterial from material_apoyo m, tipos_material t
									where t.cod_tipomaterial=m.cod_tipo_material and m.codigo_material='$codigo_item'";
				$resp_tipomaterial=mysql_query($sql_tipomaterial);
				$dat_tipomaterial=mysql_fetch_array($resp_tipomaterial);
				$nombre_tipomaterial=$dat_tipomaterial[0];
				$cadena_mostrar="<tr><td>$indice</td><td><strong>$nombre_item</strong></td><td>&nbsp;$nombre_linea</td><td>&nbsp;$nombre_tipomaterial</td>";
			}

			$sql_almacenes="select a.cod_almacen, a.cod_ciudad, a.nombre_almacen, c.descripcion from almacenes a, ciudades c where a.cod_ciudad=c.cod_ciudad
			order by c.descripcion";
			$cant_total=0;
			$resp_almacenes=mysql_query($sql_almacenes);
			while($datos_almacenes=mysql_fetch_array($resp_almacenes))
			{	$rpt_almacen=$datos_almacenes[0];
				$nombre_almacen=$datos_almacenes[2];
				$nombre_ciudad=$datos_almacenes[3];

				$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
				where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$rpt_almacen'
				and id.cod_material='$codigo_item' and i.ingreso_anulado=0 and i.grupo_ingreso='$tipo_item'";
				$resp_ingresos=mysql_query($sql_ingresos);
				$dat_ingresos=mysql_fetch_array($resp_ingresos);
				$cant_ingresos=$dat_ingresos[0];
				$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
				where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$rpt_almacen'
				and sd.cod_material='$codigo_item' and s.salida_anulada=0 and s.grupo_salida='$tipo_item'";
				$resp_salidas=mysql_query($sql_salidas);
				$dat_salidas=mysql_fetch_array($resp_salidas);
				$cant_salidas=$dat_salidas[0];
				$stock2=$cant_ingresos-$cant_salidas;
				if($stock2<0){
						$stock2=0;
				}

				$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
				where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$rpt_almacen'";
				$resp_stock=mysql_query($sql_stock);
				$dat_stock=mysql_fetch_array($resp_stock);
				$stock_real=$dat_stock[0];
				if($stock_real=="")
				{	$stock_real=0;
				}
				$cant_total=$cant_total+$stock2;
				if($tipo_item==1)
				{	$sql_linea="select * from muestras_medicas where codigo='$codigo_item' and codigo_linea in ($rpt_linea)";
					$resp_linea=mysql_query($sql_linea);
				}
				if($tipo_item==2)
				{	$sql_linea="select * from material_apoyo where codigo_material='$codigo_item' and codigo_linea in ($rpt_linea)";
					$resp_linea=mysql_query($sql_linea);
				}
				$num_filas=mysql_num_rows($resp_linea);
				$cadena_mostrar.="<td align='right'>$stock2</td>";
			}
			$cadena_mostrar.="<td align='right'>$cant_total</td></tr>";
			if($rpt_linea!=0 and $num_filas==0)
			{	//no se muestra nada
			}
			else
			{	if($rpt_ver==1)
				{	echo $cadena_mostrar;
				}
				if($rpt_ver==2 and $cant_total>0)
				{	echo $cadena_mostrar;
				}
				if($rpt_ver==3 and $cant_total==0)
				{	echo $cadena_mostrar;
				}

				$indice++;
			}
		}
		echo "</table>";
		
		include("imprimirInc.php");

}
?>