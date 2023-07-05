<html>
<body>

<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
	<tr><th>Tipo Doc</th><th>Nro.</th><th>Fecha</th><th>Monto</th><th>A Cuenta</th><th>Saldo</th><th>Monto a Pagar</th><th>Nro. Doc. Pago</th></tr>
<?php
require("../conexionmysqli2.inc");
require("../funciones.php");

$codCliente=$_GET['codCliente'];

$sql="select s.`cod_salida_almacenes`, s.`nro_correlativo`, 
	(select td.`nombre` from `tipos_docs` td where td.`codigo`=s.cod_tipo_doc),
	s.`fecha`, s.`monto_final`, s.`monto_cancelado`
	from `salida_almacenes` s where s.`cod_cliente`='$codCliente' and s.`salida_anulada`=0 and 
	s.`monto_final`>s.`monto_cancelado` and  s.cod_almacen=1000 and s.cod_tiposalida=1001 and s.cod_tipopago=4 order by s.`fecha`;";
$resp=mysqli_query($enlaceCon, $sql);
$numFilas=mysqli_num_rows($resp);

echo "<input type='hidden' name='nroFilas' id='nroFilas' value='$numFilas'>";

$i=1;
while($dat=mysqli_fetch_array($resp)){
	$codigo=$dat[0];
	$numero=$dat[1];
	$nombreDoc=$dat[2];
	$fecha=$dat[3];
	$monto=$dat[4];
	$montoCancelado=$dat[5];
	$saldo=$monto-$montoCancelado;
	
	$montoV=redondear2($monto);
	$montoCanceladoV=redondear2($montoCancelado);
	$saldoV=redondear2($saldo);
	
	echo "<tr>
		<input type='hidden' value='$codigo' name='codCobro$i' id='codCobro$i'>
		<td>$nombreDoc</td>
		<td>$numero</td>
		<td>$fecha</td>
		<td>$montoV</td>
		<td>$montoCanceladoV</td>
		<td>$saldoV</td>
		<input type='hidden' value='$saldo' name='saldo$i' id='saldo$i'>
		<td align='center'><input type='text' class='texto' name='montoPago$i' id='montoPago$i' size='10' onKeyPress='javascript:return solonumeros(event)' value='0'></td>
		<td align='center'><input type='text' class='texto' name='nroDoc$i' id='nroDoc$i' size='10' onKeyPress='javascript:return solonumeros(event)' value='0'></td>
		</tr>";
	$i++;
}

?>
</table>

</body>
</html>