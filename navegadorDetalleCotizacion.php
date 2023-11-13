<?php

	require("conexionmysqli2.inc");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");

	$codigoCotizacion=$_GET["codigo"];

	$sqlEmpresa="select nombre, nit, direccion from datos_empresa";
	$respEmpresa=mysqli_query($enlaceCon,$sqlEmpresa);
	$datEmpresa=mysqli_fetch_array($respEmpresa);
	$nombreEmpresa=$datEmpresa[0];//$nombreEmpresa=mysql_result($respEmpresa,0,0);
	$nitEmpresa=$datEmpresa[1];//$nitEmpresa=mysql_result($respEmpresa,0,1);
	$direccionEmpresa=$datEmpresa[2];//$direccionEmpresa=mysql_result($respEmpresa,0,2);
	
	$logoEmpresa=obtenerValorConfiguracion($enlaceCon,13);

	$sql="select s.cod_salida_almacenes, s.fecha, 'cotizacion', s.observaciones,
	s.nro_correlativo, s.cod_chofer, s.descuento, s.monto_total, s.monto_final
	FROM cotizaciones s 
	where s.cod_almacen='$global_almacen' and s.cod_salida_almacenes='$codigoCotizacion'";

	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];

	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$nro_correlativo=$dat[4];
	$cod_funcionario=$dat[5];
	$descuentoVenta=$dat[6];



	$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
	$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
	$nombreFuncionario=mysqli_result($respResponsable,0,0);
	
		
	echo "<table width='80%' border='0' align='center'>
	<tr>
		<td width='20%' align='left'><img src='imagenes/$logoEmpresa' width='100'></td>
		<td width='60%' align='center'><span style='font-size:20px; color:#000011; align:center; width: 600px;'>$nombreEmpresa<br>Cotización</span></td>
		<td width='20%'></td>
	<tr>";
	
	//echo "<h2 align='center'>Cotización</h2>";


	echo "<table class='texto' align='center'>";
	echo "<tr>
	<th align='right' width='30%'>Fecha de Cotización:  $fecha_salida</th>
	<th align='center' width='30%'>Número de Cotización:  $nro_correlativo</th>
	</tr>";
	
	echo "<tr><th>Responsable: $nombreFuncionario</th><th colspan='2'>Observaciones: $obs_salida</th></tr>";
			
	echo "</table><br>";

	


	echo "<table border='0' class='texto' cellspacing='0' width='90%' align='center'>";
	echo "<tr><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Descuento</th><th>Monto Producto</th></tr>";
		
$sqlDetalle="select m.codigo_material, sum(s.`cantidad_unitaria`), m.`descripcion_material`, s.`precio_unitario`, 
		sum(s.`descuento_unitario`), sum(s.`monto_unitario`) from `cotizaciones_detalle` s, `material_apoyo` m where 
		m.`codigo_material`=s.`cod_material` and s.`cod_salida_almacen`='$codigoCotizacion' 
		group by s.cod_material
		order by s.orden_detalle";
$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);

$montoTotal=0;$descuentoVentaProd=0;
while($datDetalle=mysqli_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$nombreMat=$datDetalle[2];
	$precioUnit=$datDetalle[3];
	$descUnit=$datDetalle[4];
	//$montoUnit=$datDetalle[5];
	$montoUnit=($cantUnit*$precioUnit)-$descUnit;
	
	//recalculamos el precio unitario para mostrar en la factura.
	//$precioUnitFactura=$montoUnit/$cantUnit;
	$precioUnitFactura=($cantUnit*$precioUnit)/$cantUnit;
	$cantUnit=redondear2($cantUnit);
	$precioUnit=redondear2($precioUnit);
	$montoUnit=redondear2($montoUnit);
	
	$precioUnitFactura=redondear2($precioUnitFactura);

	// - $descUnit
	$descUnit=redondear2($descUnit);	
	$descuentoVentaProd+=$descUnit;
	$montoUnitProd=($cantUnit*$precioUnit);

	$montoUnitProdDesc=$montoUnitProd-$descUnit;
	$montoUnitProdDesc=redondear2($montoUnitProdDesc);

	$montoUnitProd=redondear2($montoUnitProd);

	?>
    <tr class="arial-7">
    	<td align="left">(<?=$codInterno?>)</td>
    	<td align="left"><?=$nombreMat?></td>
    	<td align="right"><?="$cantUnit"?></td>
    	<td align="right"><?="$precioUnitFactura"?></td>
    	<td align="right"><?="$descUnit"?></td>
    	<td align="right"><?="$montoUnitProdDesc"?></td>
    </tr>
	<?php
	$montoTotal=$montoTotal+$montoUnitProdDesc;
	$montoFinal=$montoTotal-$descuentoVenta;

	$montoTotalF=formatonumeroDec($montoTotal);
	$descuentoVentaF=formatonumeroDec($descuentoVenta);
	$montoFinalF=formatonumeroDec($montoFinal);
}
?>
	<tr class="arial-7">
    	<td colspan="5" align="right"><b>Total</b></td>
    	<td align="right"><b><?=$montoTotalF;?></b></td>
    </tr>

	<tr class="arial-7">
    	<td colspan="5" align="right"><b>Descuento</b></td>
    	<td align="right"><b><?=$descuentoVentaF;?></b></td>
    </tr>

	<tr class="arial-7">
    	<td colspan="5" align="right"><b>Total Final Cotización</b></td>
    	<td align="right"><b><?=$montoFinalF;?></b></td>
    </tr>

	</table>
