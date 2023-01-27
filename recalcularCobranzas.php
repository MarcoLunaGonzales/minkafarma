<?php

require('conexionmysqli2.inc');
require('funciones.php');


$sql="select distinct(p.codigo_material), cod_precio, precio from precios p where p.cod_precio=1";
$resp=mysqli_query($enlaceCon, $sql);
while($dat=mysql_fetch_array($resp)){
	$codProducto=$dat[0];
	$precio=$dat[2];

	echo $codProducto." ".$precio."<br>";

	$sqlVeri="SELECT c.cod_cobro from cobros_detalle c where c.cod_venta=$codSalida";
	$respVeri=mysql_query($sqlVeri);
	$nroFilasVeri=mysql_num_rows($respVeri);
	if($nroFilasVeri>0){
		$codCobro=mysql_result($respVeri,0,0);

		mysql_query("UPDATE cobros_detalle set monto_detalle=$montoFinal where cod_cobro=$codCobro ");
		mysql_query("UPDATE cobros_cab set monto_cobro=$montoFinal where cod_cobro=$codCobro ");

		mysql_query("UPDATE salida_almacenes set monto_cancelado=$montoFinal where cod_salida_almacenes=$codSalida");

	}


}

?>