<?php
require('conexion.inc');

require("estilos_almacenes.inc");

$sqlVeri="select estado_salida from salida_almacenes where cod_salida_almacenes='$codigo_registro'";
$respVeri=mysql_query($sqlVeri);
$estadoSalida=mysql_result($respVeri,0,0);

	$sql="update salida_almacenes set estado_salida=4 where cod_salida_almacenes=$codigo_registro";
	$resp=mysql_query($sql);
echo "<script language='Javascript'>
    alert('Los datos fueron Modificados.');
    location.href='navegadorVentas.php';
    </script>";

?>