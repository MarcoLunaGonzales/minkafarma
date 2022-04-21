<?php

require("conexionmysqli.php");
require("estilos.inc");

$codigo=$_POST["codigo"];
$nombre=$_POST["nombre"];

$sql="update acciones_terapeuticas set nombre_accionterapeutica='$nombre' where cod_accionterapeutica='$codigo'";
$resp=mysqli_query($enlaceCon,$sql);

echo "<script language='Javascript'>
			alert('El proceso se completo correctamente.');
			location.href='navegador_accionester.php';
			</script>";
			
?>