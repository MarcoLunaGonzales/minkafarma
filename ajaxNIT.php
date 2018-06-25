<?php
require("conexion.inc");
$codCliente=$_GET['codCliente'];

$sql="select c.`nit_cliente` from `clientes` c where c.`cod_cliente`='$codCliente'";
$resp=mysql_query($sql);

$nombre="";
while($dat=mysql_fetch_array($resp)){
	$nombre=$dat[0];
}

echo "<input type='text' value='$nombre' name='nitCliente' id='nitCliente'>";

?>
