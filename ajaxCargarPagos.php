<html>
<body>

<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
	<tr><th>Nro. OC</th><th>Fecha OC</th><th>Monto OC</th><th>A Cuenta</th><th>Saldo OC</th><th>Monto a Pagar</th><th>Nro. Doc. Pago</th></tr>
<?php
require("conexion.inc");
$codProveedor=$_GET['codProveedor'];

$sql="select o.cod_orden, o.nro_orden, o.cod_proveedor, o.fecha_orden, o.monto_orden, o.monto_cancelado,
	(select p.nombre_proveedor from proveedores p where p.cod_proveedor=o.cod_proveedor) as proveedor 
	from orden_compra o
	where o.cod_proveedor='$codProveedor' and cod_estado=1 and monto_orden>monto_cancelado order by o.fecha_orden";
$resp=mysql_query($sql);

$numFilas=mysql_num_rows($resp);

echo "<input type='hidden' name='nroFilas' id='nroFilas' value='$numFilas'>";

$i=1;
while($dat=mysql_fetch_array($resp)){
	$codigo=$dat[0];
	$numero=$dat[1];
	$codProveedor=$dat[2];
	$fecha=$dat[3];
	$montoOrden=$dat[4];
	$montoCancelado=$dat[5];
	$saldoOC=$montoOrden-$montoCancelado;
	$nombreProveedor=$dat[6];
	
	echo "<tr>
		<input type='hidden' value='$codigo' name='codOrden$i' id='codOrden$i'>
		
		<td>$numero</td>
		<td>$fecha</td>
		<td>$montoOrden</td>
		<td>$montoCancelado</td>
		<td>$saldoOC</td>
		<input type='hidden' value='$saldoOc' name='saldo$i' id='saldo$i'>
		<td align='center'><input type='text' class='texto' name='montoPago$i' id='montoPago$i' size='10' onKeyPress='javascript:return solonumeros(event)' value='0'></td>
		<td align='center'><input type='text' class='texto' name='nroDoc$i' id='nroDoc$i' size='10' onKeyPress='javascript:return solonumeros(event)' value='0'></td>
		</tr>";
	$i++;
}

?>
</table>

</body>
</html>