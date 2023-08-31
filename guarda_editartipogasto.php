<?php
require("conexionmysqli.php");
require("estilos_almacenes.inc");

$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$estado=$_POST['estado'];

$sql_upd=mysqli_query($enlaceCon,"update tipos_gasto set nombre_tipogasto='$nombre', 
estado='$estado' where cod_tipogasto='$codigo'");

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_tiposgasto.php';
			</script>";
?>