<?php
/**
 * @autor: Marco Antonio Luna Gonzales
 * SISTEMA HERMES
 * * @copyright 2008 
*/
echo"<head><title>Modulo Regional de Inventarios</title><link href='stilos.css' rel='stylesheet' type='text/css'></head>";  
echo "<div id='Layer1' style='position:absolute; left:0px; top:0px; width:1000px; height:50 px; z-index:1; background-color: #000000; layer-background-color: #000000; border: 1px none #000000;'><img src='imagenes/cab_peque.jpg'>";
echo "</div>";
echo "<body background='imagenes/fondo_pagina.jpg'>";
echo "<div style='position:absolute; left:0px; top:100px; width:1000px; border: 1px none #000000;'>";
require("conexion.inc");
echo"<script language='javascript'>";
echo"function tamanonormal()";
echo"{ ";
echo"window.resizeTo(1024,768);";
echo"window.moveTo(0,0);";
echo"window.scrollbars=false;";
echo"window.resizable=false;";
echo"window.menubar=false;";
echo"}";
echo "tamanonormal();";
echo"</script>";
$sql="select * from ciudades where cod_ciudad=102 order by descripcion";
$resp=mysql_query($sql);
echo "<center><table border='0' class='textotit'><tr><th>Ingreso Hermes Inventarios Regional<br>Elegir Territorio</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0' width='40%'>";
echo "<tr><th>Territorio</th><th>&nbsp;</th></tr>";
$indice_tabla=1;
while($dat=mysql_fetch_array($resp))
{
	$cod_ciudad=$dat[0];
	$desc_ciudad=$dat[1];
	echo "<tr><td>&nbsp;$desc_ciudad</td><td align='center'><a href='cookie_almacenregionalconsulta.php?cod_ciudad=$cod_ciudad'>Ingresar >></a></td></tr>";
	$indice_tabla++;
}
echo "</table></center><br>";
?>