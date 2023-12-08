<?php
require_once 'conexionmysqli.php';
require_once 'estilos_almacenes.inc';
require_once 'funciones.php';

$rptItem 	  = empty($_POST['rpt_item']) ? '' : $_POST['rpt_item'];
$tipo_ingreso = empty($_POST['tipo_ingreso']) ? '' : implode(', ', $_POST['tipo_ingreso']);
$fecha_ini    = empty($_POST['exafinicial']) ? '' : $_POST['exafinicial'];
$fecha_fin 	  = empty($_POST['exaffinal']) ? '' : $_POST['exaffinal'];

$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";

	$sql_tipo_ingreso="SELECT nombre_tipoingreso from tipos_ingreso where cod_tipoingreso in ($tipo_ingreso)";
	// echo $sql_tipo_ingreso;
	$resp_tipo_ingreso=mysqli_query($enlaceCon, $sql_tipo_ingreso);
	$nombre_tipoingresomostrar="";
	while($datos_tipo_salida=mysqli_fetch_array($resp_tipo_ingreso)){
		$nombre_tipoingresomostrar.=$datos_tipo_salida[0]." - ";	
	}

	$detalle_ingreso = "";

	// DETALLE ITEMS
	// $detalle_ingreso.="<table border=0 cellspacing='0' align='center' class='textomini' width='100%'>";
	// $detalle_ingreso.="<tr><th width='70%'>Material</th><th width='30%'>Cantidad</th></tr></table>";
	// FIN DETALLE ITEMS

	echo "<h1>Reporte Ingresos Almacen - Costo 0</h1>
	<h1>$nombre_tipoingresomostrar Fecha inicio: <strong>$fecha_ini</strong> Fecha final: <strong>$fecha_fin</strong><br>$txt_reporte</h1>";

	//desde esta parte viene el reporte en si
	//$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
	//$fecha_finconsulta=cambia_formatofecha($fecha_fin);
	
	$fecha_iniconsulta=$fecha_ini;
	$fecha_finconsulta=$fecha_fin;
	
	
	$sql="SELECT i.cod_ingreso_almacen, 
				i.fecha, 
				ti.nombre_tipoingreso, 
				i.observaciones, 
				i.nota_entrega, 
				i.nro_correlativo, 
				i.ingreso_anulado, 
				i.nro_factura_proveedor,
				(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor)as proveedor,
				id.costo_almacen,
				id.cantidad_unitaria,
				ma.codigo_material,
				ma.descripcion_material
	FROM ingreso_almacenes i, tipos_ingreso ti, material_apoyo ma, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen 
	and i.cod_tipoingreso=ti.cod_tipoingreso 
	AND ma.codigo_material = id.cod_material 
	and i.cod_almacen='$rpt_almacen' 
	and i.fecha>='$fecha_iniconsulta' 
	and i.fecha<='$fecha_finconsulta' 
	and i.ingreso_anulado=0 
	and (id.costo_almacen = 0 OR id.costo_almacen IS NULL)";
	if($tipo_ingreso!='')
	{	$sql.=" and i.cod_tipoingreso IN ($tipo_ingreso)";
	}
	$sql.="ORDER BY i.nro_correlativo";
	// echo $sql;
	
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<center><br><table class='texto' width='100%'>";
	echo "<tr class='textomini'><th>Nro. Ingreso</th><th>Proveedor</th><th>Nro. Factura</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th><th>Estado</th><th>Cod.Producto</th><th>Producto</th><th>Cantidad</th><th>Costo</th><th>$detalle_ingreso</th></tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_ingreso=$dat[1];
		$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
		$nombre_tipoingreso=$dat[2];
		$obs_ingreso=$dat[3];
		$nota_entrega=$dat[4];
		$nro_correlativo=$dat[5];
		$anulado=$dat[6];
		$nroFacturaProv=$dat[7];
		$nombreProveedor=$dat[8];
		$costo_ingreso = $dat[9];
		
		$productoCantidad = $dat[10];
		$productoCodigo   = $dat[11];
		$productoNombre   = $dat[12];
		
		echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
		$bandera=0;
		$sql_verifica_movimiento="select s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi
		where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
		$resp_verifica_movimiento=mysqli_query($enlaceCon, $sql_verifica_movimiento);
		$num_filas_movimiento=mysqli_num_rows($resp_verifica_movimiento);
		if($num_filas_movimiento!=0)
		{	$estado_ingreso="Con Movimiento";
		}
		if($anulado==1)
		{	$estado_ingreso="Anulado";
		}
		if($num_filas_movimiento==0 and $anulado==0)
		{	$estado_ingreso="Sin Movimiento";
		}
		//desde esta parte sacamos el detalle del ingreso
		$sql_detalle="select i.cod_material, i.cantidad_unitaria from ingreso_detalle_almacenes i
		where i.cod_ingreso_almacen='$codigo'";
		// echo $sql_detalle;
		$resp_detalle=mysqli_query($enlaceCon, $sql_detalle);
		$bandera=0;
		$detalle_ingreso="";
		$detalle_ingreso.="<table border=0 cellspacing='0' align='center' class='textomini' width='100%'>";

		// DETALLE ITEMS
		//$detalle_ingreso.="<tr><th width='70%'>Material</th><th width='30%'>Cantidad</th></tr>";
		// $numFilas = 0;
		// if ($resp_detalle) {
		// 	$numFilas = mysqli_num_rows($resp_detalle);
		// }
		// if($numFilas>0){
		// 	while($dat_detalle=mysqli_fetch_array($resp_detalle))
		// 	{	$cod_material=$dat_detalle[0];
		// 		$cantidad_unitaria=$dat_detalle[1];
		// 		$cantidad_unitaria=redondear2($cantidad_unitaria);
		// 		$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
		// 		$resp_nombre_material=mysqli_query($enlaceCon, $sql_nombre_material);
		// 		$dat_nombre_material=mysqli_fetch_array($resp_nombre_material);
		// 		$nombre_material=$dat_nombre_material[0];
		// 		$detalle_ingreso.="<tr><td width='70%'>$nombre_material </td><td align='center'  width='30%'>$cantidad_unitaria</td></tr>";
		// 	}
		// }
		// FIN DETALLE ITEMS

		$detalle_ingreso.="</table>";
		echo "<tr>
			<td align='center'>$nro_correlativo</td>
			<td align='left'>$nombreProveedor</td>
			<td align='center'>$nroFacturaProv</td>
			<td align='center'>$fecha_ingreso_mostrar</td>
			<td>$nombre_tipoingreso</td>
			<td>&nbsp;$obs_ingreso</td>
			<td>&nbsp;$estado_ingreso</td>
			<td align='center'>$productoCodigo</td>
			<td>$productoNombre </td>
			<td>$productoCantidad</td>
			<td>$costo_ingreso</td>
			<td align='center'>$detalle_ingreso</td>
		</tr>";
	}
	echo "</table></center><br>";
?>