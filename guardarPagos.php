<?php
require("conexion.inc");
require("estilos_almacenes.inc");


$fecha=date("Y-m-d");
$hora=date("H:i:s");

$proveedor=$_POST["proveedor"];
$nroFilas=$_POST["nroFilas"];
$observaciones=$_POST["observaciones"];


for($i=1;$i<=$nroFilas;$i++)
{   	
	$sql="SELECT cod_pago FROM pagos_oc ORDER BY cod_pago DESC";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$num_filas=mysql_num_rows($resp);
	if($num_filas==0)
	{   $codigo=1;
	}
	else
	{   $codigo=$dat[0];
		$codigo++;
	}



	$codOC=$_POST["codOrden$i"];	
	$montoPago=$_POST["montoPago$i"];
	$nroDoc=$_POST["nroDoc$i"];
	
	if($montoPago>0){
		$sql_inserta="INSERT INTO `pagos_oc`(`cod_pago`, `cod_ordencompra`,`fecha_pago`, 
			`nro_doc`, `observaciones`, `monto_pago`, `cod_proveedor`, 
			`cod_estado`)
			values ('$codigo', '$codOC', '$fecha', '$nroDoc', '$observaciones', '$montoPago', '$proveedor', 1)";
	
		echo $sql_inserta;
		$sql_inserta=mysql_query($sql_inserta);
		//actualizamos la tabla ordenes de compra
		$sqlUpd="update orden_compra set monto_cancelado=monto_cancelado+$montoPago where cod_orden='$codOC'";
		$respUpd=mysql_query($sqlUpd);

	}	
	echo $i;
}


echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron insertados correctamente.');";
echo "    location.href='navegador_pagos.php';";
echo "</script>";

?>



