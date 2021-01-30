<?php

//header('Content-Type: text/html; charset=UTF-8'); 
date_default_timezone_set('America/La_Paz');

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

$conexion=mysql_connect("localhost","root","4868422Marco");
$bd=mysql_select_db("carmelita");
//$conexion=mysql_connect("www.minkasoftware.com","minkauserbd","4868422Marco$");
//$bd=mysql_select_db("minkafarmacia");


$sqlX="SET NAMES 'utf8'";
mysql_query($sqlX);
?>
