<?php
require("../conexionmysqli2.inc");
require("../estilos2.inc");
require("configModule.php");

$nombre=$_POST["nombre"];
$abreviatura=$_POST["abreviatura"];
$fechaInicio=$_POST["fecha_inicio"];
$fechaFinal=$_POST["fecha_final"];

$sql="insert into $table (nombre, abreviatura, estado_campania, fecha_inicio, fecha_fin) values('$nombre','$abreviatura','1','$fechaInicio','$fechaFinal')";
//echo $sql;
$sql_inserta=mysqli_query($enlaceCon,$sql);

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='$urlList2';
			</script>";

?>