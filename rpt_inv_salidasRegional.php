<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";
$cod_tiposalida=$rpt_tiposalida;
$nombre_tiposalida=$rpt_tiposalida1;
$codigo_almacen=$rpt_almacen;
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);

if($tipo_item==1)
{$nombre_item="Muestra Médica";}else{$nombre_item="Material de Apoyo";}
$nombre_lineas=$rpt_linea1;
$codigo_lineas=$rpt_linea;
echo "<table align='center' class='textotit'><tr><td align='center'>Reporte Salida de Productos x Regional<br>Tipo de Salida: $nombre_tiposalida<br> Fecha inicio: <strong>$fecha_ini</strong> Fecha final: <strong>$fecha_fin</strong> Tipo de Item: <strong>$nombre_item</strong><br>Linea: <strong>$nombre_lineas</strong><br>$txt_reporte</th></tr></table>";

	$sql_regional="select c.cod_ciudad, c.descripcion, a.cod_almacen from ciudades c, almacenes a where a.cod_ciudad=c.cod_ciudad order by c.descripcion";
	$resp_regional=mysql_query($sql_regional);
	echo "<table border=1 class='texto' align='center'>";
	echo"<tr><th>Regional</th><th>Producto</th><th>Cantidad</th></tr>";		
	while($dat_regional=mysql_fetch_array($resp_regional)){
		$codRegional=$dat_regional[0];
		$nombreRegional=$dat_regional[1];
		$codAlmacenDestino=$dat_regional[2];

		echo "<tr><th>$nombreRegional</th><td>&nbsp;</td></tr>";
		if($tipo_item==1){
		$sqlProducto="select CONCAT(m.descripcion,' ', m.presentacion) as producto, sum(cantidad_unitaria) as cantidad from salida_almacenes s, salida_detalle_almacenes sd, muestras_medicas m
			where s.cod_salida_almacenes=sd.cod_salida_almacen and 
			sd.cod_material=m.codigo and m.codigo_linea in ($codigo_lineas) and s.cod_almacen=$codigo_almacen and s.almacen_destino=$codAlmacenDestino and 
			s.cod_tiposalida in ($cod_tiposalida) and s.grupo_salida=1 and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' 
			and s.salida_anulada=0 group BY sd.cod_material";		
		}
		else{
		$sqlProducto="select descripcion_material, sum(cantidad_unitaria) as cantidad from salida_almacenes s, salida_detalle_almacenes sd, material_apoyo m
			where s.cod_salida_almacenes=sd.cod_salida_almacen and 
			sd.cod_material=m.codigo_material and m.codigo_linea in ($codigo_lineas) and s.cod_almacen=$codigo_almacen and s.almacen_destino=$codAlmacenDestino and 
			s.cod_tiposalida in ($cod_tiposalida) and s.grupo_salida=2 and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' 
			and s.salida_anulada=0 group BY sd.cod_material";		
		}
		$respProducto=mysql_query($sqlProducto);
		$cantidadTotalRegional=0;
		while($datProducto=mysql_fetch_array($respProducto)){
			$nombreProducto=$datProducto[0];
			$cantidad=$datProducto[1];
			$cantidadTotalRegional=$cantidadTotalRegional+$cantidad;
			echo "<tr><td>&nbsp;</td><td>$nombreProducto</td><td align='right'>$cantidad</td></tr>";
		}
		echo "<tr><td>&nbsp;</td><th>Total</th><th align='right'>$cantidadTotalRegional</th></tr>";
	}
	echo "</table>";

?>