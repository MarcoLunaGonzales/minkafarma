<?php
require_once 'conexionmysqli.php';
require_once 'function_formatofecha.php';
require_once 'funciones.php';

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$codGasto=$_GET['codigo_registro'];

//anulamos el registro

$sql="UPDATE gastos set estado=2 where cod_gasto='$codGasto'";
$resp=mysqli_query($enlaceCon,$sql);

echo "<script>
	alert('Se anulo el gasto.');
	location.href='navegador_gastos.php';
</script>";
?>

