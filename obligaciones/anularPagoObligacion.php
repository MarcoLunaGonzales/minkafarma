<html>
    <head>

    </head>
    <body>
<?php

require("../conexionmysqli.php");
require('../function_formatofecha.php');
require("../estilos_almacenes.inc");
require('../home_almacen.php');
require('../funciones.php');

$codPago=$_GET['codigo_registro'];

//anulamos el registro

$sql="UPDATE pagos_proveedor_cab set cod_estado=2 where cod_pago='$codPago' ";
//echo $sql;
$resp=mysqli_query($enlaceCon,$sql);


$sqlDet="SELECT cod_ingreso_almacen, monto_detalle from pagos_proveedor_detalle where cod_pago='$codPago' ";
$respDet=mysqli_query($enlaceCon,$sqlDet);

while($datDet=mysqli_fetch_array($respDet)){
	$codIngreso = $datDet[0];
	$montoDet	= $datDet[1];

	$sqlUpd="UPDATE ingreso_almacenes set monto_cancelado = ROUND(monto_cancelado - $montoDet, 2) where cod_ingreso_almacen=$codIngreso ";
	//echo $sqlUpd;
	$respUpd=mysqli_query($enlaceCon,$sqlUpd);

}

?>

<script>
	alert('Se anulo el pago.');
	location.href='navegadorObligaciones.php';
</script>

    </body>
</html>
