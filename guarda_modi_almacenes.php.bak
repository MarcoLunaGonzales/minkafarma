<?php
require("conexion.inc");
require("estilos.inc");
$sql_upd=mysql_query("update almacenes set nombre_almacen='$nombre_almacen', cod_ciudad='$territorio', responsable_almacen='$responsable'
 			   		where cod_almacen='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_almacenes.php';
			</script>";
?>