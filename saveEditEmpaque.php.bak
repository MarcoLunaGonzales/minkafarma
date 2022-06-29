<?php

require("conexion.inc");
require("estilos.inc");

$codigo=$_POST["codigo"];
$nombre=$_POST["nombre"];

$sql="update empaques set nombre_empaque='$nombre' where cod_empaque='$codigo'";
$resp=mysql_query($sql);

echo "<script language='Javascript'>
			alert('El proceso se completo correctamente.');
			location.href='navegador_empaques.php';
			</script>";
?>