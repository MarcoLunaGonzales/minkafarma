<?php
require("conexion.inc");
require("estilos.inc");

$codigo=$_POST["codigo"];
$txt1=$_POST["txt1"];
$txt2=$_POST["txt2"];
$txt3=$_POST["txt3"];

$izquierda=$_POST["izquierda"];
$top=$_POST["top"];
$cantidad=$_POST["cantidad"];

$sqlUpd="update etiquetas set txt1='$txt1', txt2='$txt2', txt3='$txt3', alineado_izq='$izquierda', alineado_arriba='$top', cantidad='$cantidad' 
where id='$codigo'";
$respUpd=mysql_query($sqlUpd);

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_etiquetas.php';
			</script>";
?>