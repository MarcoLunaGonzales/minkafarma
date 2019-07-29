<?php

require("conexion.inc");
require("estilos.inc");

$nombre=$_POST["nombre"];
$sql="select max(cod_forma_far)+1 from formas_farmaceuticas";
$resp=mysql_query($sql);
$codigo=mysql_result($resp,0,0);

$sql_inserta=mysql_query("insert into formas_farmaceuticas values($codigo,'$nombre',1)");

echo "<script language='Javascript'>
			alert('El proceso se completo correctamente.');
			location.href='navegador_formasfar.php';
			</script>";
?>