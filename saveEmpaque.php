<?php

require("conexionmysqli.php");
require("estilos.inc");

$nombre=$_POST["nombre"];
$sql="select max(cod_empaque)+1 from empaques";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];
//$codigo=mysqli_result($resp,0,0);

$sql_inserta=mysqli_query($enlaceCon,"insert into empaques values($codigo,'$nombre',1)");

echo "<script language='Javascript'>
			alert('El proceso se completo correctamente.');
			location.href='navegador_empaques.php';
			</script>";
?>