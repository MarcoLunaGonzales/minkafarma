<?php
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");

$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$sql_upd=mysqli_query($enlaceCon,"update $table set nombre='$nombre' where codigo='$codigo'");

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='$urlList2';
			</script>";
?>