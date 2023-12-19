<?php
require_once 'conexionmysqli.php';
require_once 'estilos_almacenes.inc';
require_once 'funciones.php';


 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$tipoSalida=$_POST['tipo_salida'];
$rptAlmacen=$_POST['rpt_almacen'];
$fechaInicio=$_POST['exafinicial'];
$fechaFinal=$_POST['exaffinal'];

$stringTipoSalida=implode(",", $tipoSalida);


$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";

$sql_tipo_salida="select nombre_tiposalida from tipos_salida where cod_tiposalida in ($stringTipoSalida)";
$resp_tipo_salida=mysqli_query($enlaceCon, $sql_tipo_salida);
$nombre_tiposalida="";
while($datos_tipo_salida=mysqli_fetch_array($resp_tipo_salida)){
	$nombre_tiposalida.=$datos_tipo_salida[0]." - ";	
}


$nombre_tiposalidamostrar="Tipo de Salida: <strong>$nombre_tiposalida</strong>";

	echo "<h1>Reporte Salidas Almacen - Costo 0</h1>
	<h1>$nombre_tiposalidamostrar Fecha inicio: <strong>$fechaInicio</strong> Fecha final: <strong>$fechaFinal</strong> <br>$txt_reporte</th></tr></table>";

	//desde esta parte viene el reporte en si
	$fecha_iniconsulta=$fechaInicio;
	$fecha_finconsulta=$fechaFinal;
	
	$rpt_almacen = implode(',', $rptAlmacen);

	$sql = "SELECT s.cod_salida_almacenes, 
			CONCAT(s.fecha, ' ', s.hora_salida) AS fecha, 
			ts.nombre_tiposalida, 
			(SELECT c.descripcion FROM ciudades c WHERE c.cod_ciudad = s.territorio_destino) AS territorio_destino, 
			(SELECT a.nombre_almacen FROM almacenes a WHERE a.cod_almacen = s.almacen_destino) AS nombre_almacen,
			s.observaciones, 
			s.estado_salida, 
			s.nro_correlativo, 
			s.salida_anulada,
			ma.codigo_material, 
			ma.descripcion_material, 
			sd.cantidad_unitaria, 
			sd.costo_almacen
	FROM salida_almacenes s, tipos_salida ts, almacenes a, material_apoyo ma, salida_detalle_almacenes sd
	WHERE s.cod_tiposalida = ts.cod_tiposalida 
	AND s.cod_almacen IN ($rpt_almacen) 
	AND a.cod_almacen = s.cod_almacen 
	AND ma.codigo_material = sd.cod_material 
	AND s.cod_salida_almacenes = sd.cod_salida_almacen 
	AND s.fecha >= '$fecha_iniconsulta' 
	AND s.fecha <= '$fecha_finconsulta' 
	AND s.cod_tiposalida IN ($stringTipoSalida) 
	AND s.salida_anulada = 0 
	AND (sd.costo_almacen = 0 OR sd.costo_almacen IS NULL) 
	ORDER BY s.nro_correlativo";

	//echo $sql;

	echo "<center><br><table class='texto'>";
	echo "<tr><th>Nro.</th><th>Fecha</th><th>Tipo de Salida</th><th>Almacen Destino</th><th>Observaciones</th><th>Cod.Producto</th><th>Producto</th><th>Cantidad</th><th>Costo</th></tr>";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp)){
		$codigo=$dat[0];
		$fecha_salida=$dat[1];
		$fecha_salida_mostrar=$fecha_salida;

		$nombre_tiposalida=$dat[2];
		$nombre_ciudad=$dat[3];
		$nombre_almacen=$dat[4];
		$obs_salida=$dat[5];
		$estado_almacen=$dat[6];
		$nro_correlativo=$dat[7];
		$salida_anulada=$dat[8];

		$codigoProducto=$dat[9];
		$nombreProducto=$dat[10];
		$cantidadUnitaria=$dat[11];
		$costoAlmacen=$dat[12];
		$cantidadUnitariaF=formatNumberInt($cantidadUnitaria);
	
		echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_almacen</td><td>&nbsp;$obs_salida</td>
		<td>$codigoProducto</td><td>$nombreProducto</td><td align='right'>$cantidadUnitariaF</td><td align='right'>$costoAlmacen</td></tr>";
	}
	echo "</table></center><br>";
?>