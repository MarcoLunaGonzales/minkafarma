<html>
<body>
<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
    <tr>
        <th>Nro.</th>
        <th>Fecha</th>
        <th>Monto</th>
        <th>A Cuenta</th>
        <th>Saldo</th>
        <th>Monto a Pagar</th>
        <th>Nro. Doc. Pago</th>
    </tr>
    <?php
    require("../conexionmysqli2.inc");
    require("../funciones.php");

    $codProveedor = $_GET['codProveedor'];

    $sql = "SELECT 
                ia.`cod_ingreso_almacen`, 
                ia.`nro_correlativo`,
                ia.`fecha`, 
                ia.`monto_ingreso`, 
                ia.`monto_cancelado`
            FROM `ingreso_almacenes` ia 
            WHERE ia.`cod_proveedor` = '$codProveedor' 
            AND ia.`ingreso_anulado` = 0 
            AND ia.`monto_ingreso` > ia.`monto_cancelado`
			AND ia.cod_almacen = 1000 
			AND ia.cod_tipoingreso = 1000
			AND ia.cod_tipopago = 4 
            ORDER BY ia.`fecha`";

    $resp = mysqli_query($enlaceCon, $sql);
    $numFilas = mysqli_num_rows($resp);

    echo "<input type='hidden' name='nroFilas' id='nroFilas' value='$numFilas'>";

    $i = 1;
    while ($dat = mysqli_fetch_array($resp)) {
        $codigo = $dat[0];
        $numero = $dat[1];
        $fecha = $dat[2];
        $monto = $dat[3];
        $montoCancelado = $dat[4];
        $saldo = $monto - $montoCancelado;

        $montoV = redondear2($monto);
        $montoCanceladoV = redondear2($montoCancelado);
        $saldoV = redondear2($saldo);
	?>
		<tr>
			<input type='hidden' value='<?=$codigo;?>' name='codIngresoAlmacen<?=$i;?>' id='codIngresoAlmacen<?=$i;?>'>
			<td><?=$numero;?></td>
			<td><?=$fecha;?></td>
			<td><?=$montoV;?></td>
			<td><?=$montoCanceladoV;?></td>
			<td><?=$saldoV;?></td>
			<input type='hidden' value='$saldo' name='saldo<?=$i;?>' id='saldo<?=$i;?>'>
			<td align='center'><input type='text' class='texto' name='montoPago<?=$i;?>' id='montoPago<?=$i;?>' size='10' onKeyPress='javascript:return solonumeros(event)' value='0'></td>
			<td align='center'><input type='text' class='texto' name='nroDoc<?=$i;?>' id='nroDoc<?=$i;?>' size='10' onKeyPress='javascript:return solonumeros(event)' value='0'></td>
		</tr>
	<?php
        $i++;
    }

    ?>
</table>

</body>
</html>