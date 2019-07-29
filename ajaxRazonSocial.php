<?php
require("conexion.inc");
$nitCliente=$_GET['nitCliente'];

$sql="select f.razon_social from facturas_venta f 
	where f.nit='$nitCliente' order by f.fecha desc limit 0,1";
$resp=mysql_query($sql);

$nombre="";
while($dat=mysql_fetch_array($resp)){
	$nombre=$dat[0];
}
echo "<input type='text' value='$nombre' name='razonSocial' id='razonSocial'>";

?>
