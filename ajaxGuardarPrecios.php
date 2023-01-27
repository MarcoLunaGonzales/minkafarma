<?php
require('conexionmysqli2.inc');
require('funciones.php');

$item=$_GET['item'];
$precios=$_GET['precios'];

$arrayPreciosModificados=[];

$arrayPrecios=explode(",",$precios);
for($i=0;$i<sizeof($arrayPrecios);$i++){
	list($precioValor, $cadena, $index, $codCiudad) = explode("|",$arrayPrecios[$i]);
	//echo $codCiudad." ".$precioValor."<br>";
	$arrayPreciosModificados[$codCiudad]=$precioValor;
}

$resp=actualizarPrecios($enlaceCon,$item,$arrayPreciosModificados);


echo "<img src='imagenes/guardarOK.png' width='30'><br>Precio Guardado!";
?>