<?php
	require("conexion.inc");
	require("estilos.inc");
	require("funciones.php");

	$sql="select distinct(o.`cod_orden`), p.`nombre_proveedor`, (o.`monto_orden`-o.`monto_cancelado`), o.`fecha_orden`, o.`fecha_vencimiento`,
		DATEDIFF(o.`fecha_orden`, CURDATE()), o.nro_factura  from `orden_compra` o, `proveedores` p 
		where o.`cod_estado`<>2 
		and p.`cod_proveedor`=o.`cod_proveedor` and 
		(o.`monto_orden`- o.`monto_cancelado`)>0 order by fecha_orden desc";

	//echo $sql;	
	$resp=mysql_query($sql);
	echo "<h1>Reporte de Ordenes de Compra por Pagar</h1>";
	echo "<center><table class='texto' id='main'>";
	echo "<tr><th>Nro. Documento</th>
	<th>Proveedor</th>
	<th>Fecha OC</th>
	<th>Saldo Bs.</th>
	<th>Dias Vencimiento</th>
	</tr>";
	$indice=1;
$sumaBs=0;
$sumaDol=0;
	while($dat=mysql_fetch_array($resp))
	{
		$codOrden=$dat[0];
		$proveedor=$dat[1];
		$saldo=$dat[2];
		$saldo=redondear2($saldo);
		
		$fechaOrden=$dat[3];
		$fechaVencimiento=$dat[4];
		$diasVenc=$dat[5];
		
		$nroDoc=$dat[6];

		$saldoDol=$saldo/6.96;
		$saldoDol=redondear2($saldoDol);

		$sumaBs=$sumaBs+$saldo;
		$sumaDol=$sumaDol+$saldoDol;

		echo "<tr><td>$nroDoc</td>";

		echo "<td align='center'>$proveedor</td>";
		echo "<td align='center'>$fechaOrden</td>";
		echo "<td align='center'>$saldo</td>";
		echo "<td align='center'>$diasVenc</td>";
		echo "</tr>";
	}
	


	echo "<tr><td>-</td>";
		echo "<td align='center'>&nbsp;</td>";
		echo "<td align='center'>&nbsp;</td>";
		echo "<td align='center'>$sumaBs</td>";
		echo "<td align='center'>&nbsp;</td>";
		echo "</tr>";

	echo "</table></center><br>";

?>