<?php

require("conexionmysqli.php");
require("estilos.inc");

$codigo=$_POST["codigo"];
$nombre=$_POST["nombre"];

$sql="update formas_farmaceuticas set nombre_forma_far='$nombre' where cod_forma_far='$codigo'";
$resp=mysqli_query($enlaceCon,$sql);

echo "<script language='Javascript'>
			alert('El proceso se completo correctamente.');
			location.href='navegador_formasfar.php';
			</script>";
			
?>