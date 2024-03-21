<?php
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=archivo.xls");
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.php');
require("funciones.php");
 

$rptAlmacen=$_POST["rpt_almacen"];
$rptGrupo=$_POST["rpt_grupo"];

$rptFechaInicio=$_POST["rpt_ini"];
$rptFechaFinal=$_POST["rpt_fin"];

$fechaInicioPivot = $rptFechaInicio;
//restamos un dia
$fechaInicioPivot=date("Y-m-d",strtotime($fechaInicioPivot."- 1 days")); 
//echo "inicio pivot: ".$fechaInicioPivot;

//echo "almacen: ".$rptAlmacen;

$rptGrupoS="";
if($rptGrupo!=""){
	$rptGrupoS=implode(",",$rptGrupo);
}

$rptAlmacenS="";
if($rptAlmacen!=""){
	$rptAlmacenS=implode(",",$rptAlmacen);
}
$rptAlmacenPrincipal=$rptAlmacen[0];


$fecha_reporte=date("Y-m-d");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";

	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen in ($rptAlmacenS)";
	$resp_nombre_almacen=mysqli_query($enlaceCon,$sql_nombre_almacen);
	$nombre_almacen="";
	while($datos_nombre_almacen=mysqli_fetch_array($resp_nombre_almacen)){
		$nombre_almacen.=$datos_nombre_almacen[0]." - ";
	}	
	
	echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Movimiento de Productos Valorado
	<br>Almacen: <strong>$nombre_almacen</strong>
	<br>Periodo: <strong>$rptFechaInicio  a  $rptFechaFinal</strong><br>$txt_reporte</td></tr></table>";
	
		//desde esta parte viene el reporte en si
		
		
		$sql_item="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion, p.nombre_proveedor 
		from material_apoyo ma, proveedores p, proveedores_lineas pl
		where ma.codigo_material<>0 and ma.estado='1' and p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=ma.cod_linea_proveedor
		and p.cod_proveedor in ($rptGrupoS) order by p.nombre_proveedor, ma.descripcion_material";

		$resp_item=mysqli_query($enlaceCon,$sql_item);
		
		echo "<br><table border=0 align='center' class='textomediano' width='70%'>
			<thead>
				<tr><th>&nbsp;</th><th>Distribuidor</th><th>COD INT</th><th>Producto</th>
				<th>Stock<br>Anterior</th>
				<th>Valor<br>Anterior</th>
				<th>Ingresos</th>
				<th>Valor<br>Ingresos</th>
				<th>Salidas</th>
				<th>Valor<br>Salidas</th>
				<th>Saldo Final</th>
				<th>Saldo<br>Final</th>
				</tr>
			</thead>";				
	
		$indice=1;
		$totalStock=0;
		while($datos_item=mysqli_fetch_array($resp_item)){	
			$codigo_item=$datos_item[0];
			$nombre_item=$datos_item[1];
			$cantidadPresentacion=$datos_item[2];
			$nombreDistribuidor=$datos_item[3];
			
			$rpt_territorio=obtenerCiudadDesdeAlmacen($rptAlmacenPrincipal);
			//$precio0=precioVenta($enlaceCon,$codigo_item,$rpt_territorio);


			$costoProducto=costoVentaGeneral($enlaceCon,$codigo_item,$rpt_territorio);
			

			$cadena_mostrar="";

			$cadena_mostrar.="<tr><td>$indice</td><td>$nombreDistribuidor</td><td>$codigo_item</td><td>$nombre_item</td>";

			$stockAnterior=stockProductoAFecha($enlaceCon, $rptAlmacenS, $codigo_item, $fechaInicioPivot);
			$valorAnterior=$stockAnterior*$costoProducto;

			//echo $stock2;
			$cantidadIngresosPeriodo=ingresosItemPeriodo($enlaceCon, $rptAlmacenS, $codigo_item, $rptFechaInicio, $rptFechaFinal);
			$valorIngresos=$cantidadIngresosPeriodo*$costoProducto;

			$cantidadSalidasPeriodo=salidasItemPeriodo($enlaceCon, $rptAlmacenS, $codigo_item, $rptFechaInicio, $rptFechaFinal);
			$valorSalidas=$cantidadSalidasPeriodo*$costoProducto;
			
			$saldoFinalItem=0;
			$saldoFinalItem=$stockAnterior+$cantidadIngresosPeriodo-$cantidadSalidasPeriodo;
			$valorFinalItem=$valorAnterior+$valorIngresos-$valorSalidas;

			if($stockAnterior<=0){	
				$cadena_mostrar.="<td align='center'>$stockAnterior</td>";
			}
			elseif($stockAnterior>0){	
				$cadena_mostrar.="<td align='center'><span class='textomedianorojo'><b>$stockAnterior</b></span></td>";
			}			
			$cadena_mostrar.="<td align='center'>$valorAnterior</td>";

			$cantidadIngresosPeriodoF=formatonumero($cantidadIngresosPeriodo);
			$cantidadSalidasPeriodoF=formatonumero($cantidadSalidasPeriodo);
			$saldoFinalItemF=formatonumero($saldoFinalItem);
			
			if($cantidadIngresosPeriodo>0){
				$cantidadIngresosPeriodoF="<span class='textomedianorojo'><b>$cantidadIngresosPeriodoF</b></span>";
			}else{
				$cantidadIngresosPeriodoF="$cantidadIngresosPeriodoF";
			}

			if($cantidadSalidasPeriodo>0){
				$cantidadSalidasPeriodoF="<span class='textomedianorojo'><b>$cantidadSalidasPeriodoF</b></span>";
			}else{
				$cantidadSalidasPeriodoF="$cantidadSalidasPeriodoF";
			}

			if($saldoFinalItem>0){
				$saldoFinalItemF="<span class='textomedianorojo'><b>$saldoFinalItem</b></span>";
			}else{
				$saldoFinalItemF="$saldoFinalItem";
			}

			$cadena_mostrar.="<td align='center'>$cantidadIngresosPeriodoF</td>
			<td align='center'>$valorIngresos</td>
			<td align='center'>$cantidadSalidasPeriodoF</td>
			<td align='center'>$valorSalidas</td>
			<td align='center'>$saldoFinalItemF</td>
			<td align='center'>$valorFinalItem</td>
			</tr>";
			
			/*$sql_linea="select * from material_apoyo where codigo_material='$codigo_item'";
			$resp_linea=mysqli_query($enlaceCon,$sql_linea);			
			$num_filas=mysqli_num_rows($resp_linea);*/
			
			echo $cadena_mostrar;
			$indice++;
		}

		echo "</table>";
		
		include("imprimirInc.php");

?>