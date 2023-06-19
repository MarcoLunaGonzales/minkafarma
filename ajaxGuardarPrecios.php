<?php
require('conexionmysqli2.inc');
require('funciones.php');

$item=$_GET['item'];
$precios=$_GET['precios'];
$porcentajes=$_GET['porcentajes'];

$arrayPreciosModificados=[];
$arrayPorcentajesModificados=[];

$arrayPrecios=explode(",",$precios);
$arrayPorcentajes=explode(",",$porcentajes);

for($i=0;$i<sizeof($arrayPrecios);$i++){
	list($precioValor, $cadena, $index, $codCiudad) = explode("|",$arrayPrecios[$i]);
	//echo $codCiudad." ".$precioValor."<br>";
	$arrayPreciosModificados[$codCiudad]=$precioValor;
}

for($i=0;$i<sizeof($arrayPorcentajes);$i++){
	list($porcentajeValor, $cadena, $index, $codCiudad) = explode("|",$arrayPorcentajes[$i]);
	//echo $codCiudad." ".$precioValor."<br>";
	$arrayPorcentajesModificados[$codCiudad]=$porcentajeValor;
}

$resp=actualizarPreciosConPorcentajes($enlaceCon,$item,$arrayPreciosModificados,$arrayPorcentajesModificados);


echo "<img src='imagenes/guardarOK.png' width='30'><br>Precio Guardado!";
?>