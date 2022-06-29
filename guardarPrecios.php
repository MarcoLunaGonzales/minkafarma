<?php

ini_set('post_max_size','100M');

require("conexionmysqli.php");
require("estilos.inc");


foreach($_POST as $nombre_campo => $valor)
{
  echo $nombre_campo ." ". $valor . "<br/>"; 
}
/*
$sql="select codigo_material from material_apoyo";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){

	$codigoMat=$dat[0];
	$precio1=$_POST[$codigoMat.'|1'];
	$precio2=$_POST[$codigoMat.'|2'];
	$precio3=$_POST[$codigoMat.'|3'];
	$precio4=$_POST[$codigoMat.'|4'];
	
	
	$sqlDel="delete from precios where codigo_material=$codigoMat";
	$respDel=mysql_query($sqlDel);
	
	$sqlInsert="insert into precios values($codigoMat, 1,$precio1)";
	echo "$sqlInsert<br>";
	$respInsert=mysql_query($sqlInsert);
	
	$sqlInsert="insert into precios values($codigoMat, 2,$precio2)";
	echo "$sqlInsert<br>";
	$respInsert=mysql_query($sqlInsert);
	
	$sqlInsert="insert into precios values($codigoMat, 3,$precio3)";
	echo "$sqlInsert<br>";
	$respInsert=mysql_query($sqlInsert);
	
	$sqlInsert="insert into precios values($codigoMat, 4,$precio4)";
	echo "$sqlInsert<br>";
	$respInsert=mysql_query($sqlInsert);
	
}*/

/*echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_precios.php';
			</script>";
*/

echo "termino";

?>