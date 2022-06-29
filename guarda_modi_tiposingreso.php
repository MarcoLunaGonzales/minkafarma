<?php
require("conexionmysqli.php");
require("estilos_administracion.inc");
$sql_upd=mysqli_query($enlaceCon,"update tipos_ingreso set nombre_tipoingreso='$tipo_ingreso', obs_tipoingreso='$obs_tipo_ingreso', tipo_almacen='$tipo_almacen' where cod_tipoingreso='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_tiposingreso.php';
			</script>";
?>