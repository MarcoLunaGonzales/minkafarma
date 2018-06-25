<?php
/**
 * @autor: Marco Antonio Luna Gonzales
 * SISTEMA HERMES
 * * @copyright 2008 
*/
$cod_ciudad=$_GET["cod_ciudad"];
setcookie("global_agencia",$cod_ciudad);
header("location:index_almacenregionalconsulta.html");
?>