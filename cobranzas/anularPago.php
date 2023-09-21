<?php

require("../conexionmysqli.inc");

$codPago=$_GET['codigo_registro'];

//anulamos el registro

$sql="UPDATE cobros_cab set cod_estado=2 where cod_cobro='$codPago' ";
//echo $sql;
$resp=mysqli_query($enlaceCon, $sql);


$sqlDet="SELECT cod_venta, monto_detalle from cobros_detalle where cod_cobro='$codPago' ";
$respDet=mysqli_query($enlaceCon, $sqlDet);

while($datDet=mysqli_fetch_array($respDet)){
	$codVenta=$datDet[0];
	$montoDet=$datDet[1];

	$sqlUpd="UPDATE salida_almacenes set monto_cancelado=monto_cancelado-$montoDet where cod_salida_almacenes=$codVenta ";
	//echo $sqlUpd;
	$respUpd=mysqli_query($enlaceCon, $sqlUpd);
}
?>
<script>
	alert('Se anulo el cobro.');
	location.href='navegadorCobranzas.php';
</script>